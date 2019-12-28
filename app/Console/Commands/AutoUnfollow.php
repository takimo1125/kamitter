<?php
namespace App\Console\Commands;
use App\TwitterUser;
use App\SystemManager;
use App\UnfollowTarget;
use App\UnfollowHistory;
use App\Mail\CompleteUnFollow;
use Illuminate\Console\Command;
use App\Http\Components\TwitterApi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AutoUnfollow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:unfollow';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Twitter APIの自動フォロー解除機能';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    const API_URL_UNFOLLOW = 'friendships/destroy';
    const FOLLOWER_NUMBER_FOR_ENTRY_UNFOLLOW = 1;
    const INTERVAL_HOURS = 1;
    const API_PER_A_DAY = 24 / self::INTERVAL_HOURS;
    const UNFOLLOW_RATE_MAX = 150;
    /**
     * Execute the console command.
     * フォローワー数が5000人以上いる時に実行される
     * アンフォローターゲットリストのユーザーをアンフォローしてアンフォロー履歴に保存する
     */
    public function handle()
    {
        Log::info('=====================================================================');
        Log::info('AutoUnfollow : 開始');
        Log::info('=====================================================================');
        //auto_unfollow_statusが稼動中のステータスになっているレコードを取得する
        $auto_unfollow_running_status_list = SystemManager::where('auto_unfollow_status', SystemManager::STATUS_RUNNING)->get();
        foreach ($auto_unfollow_running_status_list as $auto_unfollow_running_status_item) {
            $system_manager_id = $auto_unfollow_running_status_item->id;
            $twitter_user_id = $auto_unfollow_running_status_item->twitter_user_id;
            Log::info('#system_manager_id : ', [$system_manager_id]);
            Log::info('#twitter_user_id : ' , [$twitter_user_id]);
            //現在のフォロワー数の確認
            $follower = $this->getTwitterFollowerNum($system_manager_id, $twitter_user_id);
            if ($this->isFollowerOverEntryNumber($follower)) {
                $this->changeAutoUnfollowStatusToStop($auto_unfollow_running_status_item);
                Log::info('フォロワー数が5000人以下です。');
                Log::info('次のユーザーにスキップします');
                continue;
            }
            $unfollow_targets = UnfollowTarget::where('twitter_user_id', $twitter_user_id)->get();
            //アンフォロー実行
            $this->autoUnfollow($system_manager_id, $twitter_user_id, $unfollow_targets);
            Log::info('=====================================================================');
            Log::info('AutoUnfollow : 終了');
            Log::info('=====================================================================');
        }
    }
    /**
     * SystemManagerのauto_unfollow_statusを停止状態にする
     * @param $system_manager
     */
    private function changeAutoUnfollowStatusToStop($system_manager)
    {
        $system_manager->auto_unfollow_status = SystemManager::STATUS_STOP;
        $system_manager->save();
    }
    /**
     * フォロワー数が稼動条件数を満たしていればtrueを返す
     * @param $follower
     */
    private function isFollowerOverEntryNumber($follower)
    {
        if ($follower > self::FOLLOWER_NUMBER_FOR_ENTRY_UNFOLLOW) {
            return false;
        }
        return true;
    }
    /**
     * アンフォローターゲットリストのユーザーをアンフォローする
     * アンフォロー後に、アンフォローターゲットをアンフォロー履歴に移動する
     * @param $system_manager_id
     * @param $twitter_user_id
     * @param $unfollow_targets
     */
    private function autoUnfollow($system_manager_id, $twitter_user_id, $unfollow_targets)
    {
        Log::info('##自動アンフォロー開始');
        $twitter_user = TwitterUser::where('id', $twitter_user_id)->first();
        $unfollow_count = 0;
        $unfollow_limit = (int)(self::UNFOLLOW_RATE_MAX / self::API_PER_A_DAY);
        Log::debug('アンフォロー上限回数: ', [$unfollow_limit]);
        foreach ($unfollow_targets as $unfollow_target) {
            $api_result = (object)$this->fetchAutoUnfollow($twitter_user, $unfollow_target->twitter_id);
            $flg_skip_to_next_user = TwitterApi::handleApiError($api_result, $system_manager_id, $twitter_user_id);
            if ($flg_skip_to_next_user === true) {
                return;
            }
            $this->moveUnfollowTargetsToUnfollowHistories($twitter_user_id, $unfollow_target);
            $unfollow_count++;
            if ($unfollow_count >= $unfollow_limit){
                return;
            }
        }
        // 全てのアンフォロワーターゲットをアンフォローした時点で自動アンフォロー完了
        $target_quantity = UnfollowTarget::where('twitter_user_id', $twitter_user_id)->count();
        if($target_quantity === 0){
            Log::info('##アンフォローワーターゲットのフォローが完了しました');
            $this->sendMail($system_manager_id, $twitter_user_id);
        }
        Log::info('##自動アンフォロー完了');
    }
    /**
     * 自動アンフォロー完了メールを送信する
     * @param $system_manager_id
     * @param $twitter_user_id
     */
    private function sendMail($system_manager_id, $twitter_user_id)
    {
        $system_manager = SystemManager::with('user')->find($system_manager_id);
        $twitter_user = TwitterUser::find($twitter_user_id);
        $user = $system_manager->user;
        Mail::to($user)->send(new CompleteUnFollow($user, $twitter_user));
    }
    /**
     * アンフォローターゲットをアンフォロー履歴に移動する
     * @param $twitter_user_id
     * @param $unfollow_target
     */
    private function moveUnfollowTargetsToUnfollowHistories($twitter_user_id, $unfollow_target)
    {
        $unfollow_history = new UnfollowHistory();
        $unfollow_history->twitter_user_id = $twitter_user_id;
        $unfollow_history->twitter_id = $unfollow_target->twitter_id;
        $unfollow_history->save();
        $unfollow_target->delete();
    }
    /**
     * APIを使用してアンフォローする
     * @param $twitter_user
     * @param $user_id
     */
    private function fetchAutoUnfollow($twitter_user, $user_id)
    {
        Log::info('###API 自動アンフォロー開始');
        //APIに必要な変数の用意
        $token = $twitter_user->token;
        $token_secret = $twitter_user->token_secret;
        $param = [
            'user_id' => $user_id,
        ];
        //API呼び出し
        $response_json = TwitterApi::useTwitterApi('POST', self::API_URL_UNFOLLOW,
            $param, $token, $token_secret);
        Log::info('###API 自動アンフォロー完了');
        return $response_json;
    }
    /**
     * APIを使用してツイッターのフォロワー数を取得する
     * @param $system_manager_id
     * @param $twitter_user_id
     */
    private function getTwitterFollowerNum($system_manager_id, $twitter_user_id)
    {
        //API認証用のツイッターユーザー情報を取得
        $twitter_user = TwitterUser::where('id', $twitter_user_id)->first();
        $api_result = TwitterApi::fetchTwitterUserInfo($twitter_user);
        $flg_skip_to_next_user = TwitterApi::handleApiError($api_result, $system_manager_id, $twitter_user_id);
        if ($flg_skip_to_next_user === true) {
            return 0;
        }
        return $api_result->followers_count;
    }
}
