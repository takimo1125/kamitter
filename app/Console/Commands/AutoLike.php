<?php
namespace App\Console\Commands;
use App\TwitterUser;
use App\AutomaticLike;
use App\SystemManager;
use App\Mail\CompleteLike;
use Illuminate\Console\Command;
use App\Http\Components\TwitterApi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AutoLike extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:like';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Twitter APIでの自動いいね機能';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    const API_URL_SEARCH = 'search/tweets';
    const API_URL_LIKE = 'favorites/create';
    //API側の上限は900/1D
    const API_REQUEST_RATE_PER_DAY = 240;
    const DO_API_PER_A_DAY = 24;
    const INTERVAL_HOURS = 1;
    /**
     * Execute the console command.
     * 登録された自動いいねのフィルターワードごとにツイート検索を行う
     * 検索にヒットしたツイートに対していいねする
     */
    public function handle()
    {
        Log::info('=====================================================================');
        Log::info('AutoLike : 開始');
        Log::info('=====================================================================');
        //runのレコードを取得する
        //稼動中のステータスになっているのレコードを取得する
        $auto_like_running_status_list = SystemManager::where('auto_like_status', SystemManager::STATUS_RUNNING)->get();
        foreach ($auto_like_running_status_list as $auto_like_running_status_item) {
            $system_manager_id = $auto_like_running_status_item->id;
            $twitter_user_id = $auto_like_running_status_item->twitter_user_id;
            Log::info('#system_manager_id : ', [$system_manager_id]);
            Log::info('#twitter_user_id : ' , [$twitter_user_id]);
            //ユーザーごとのいいね条件配列を取得
            $auto_like_list = AutomaticLike::where('twitter_user_id', $twitter_user_id)
                ->with('twitterUser', 'filterWord')->get();
            $auto_like_list_quantity = count($auto_like_list);
            //いいね条件ごとに検索
            foreach ($auto_like_list as $auto_like) {
                $flg_skip_to_next_user = false;
                //検索にヒットしたツイート配列を取得
                $api_result = $this->fetchGetTweetListApi($auto_like, $auto_like_list_quantity);
                $flg_skip_to_next_user = TwitterApi::handleApiError($api_result, $system_manager_id, $twitter_user_id);
                if ($flg_skip_to_next_user === true) {
                    Log::notice('#APIエラーのため次のユーザーにスキップ');
                    break;
                }
                //取得したツイート一覧に対していいねをする
                foreach ($api_result->statuses as $item) {
                    $like_target_id = $item->id_str;
                    $api_result = $this->fetchLikeApi($auto_like, $like_target_id);
                    $flg_skip_to_next_user = TwitterApi::handleApiError($api_result, $system_manager_id, $twitter_user_id);
                    if ($flg_skip_to_next_user === true) {
                        Log::notice('#APIエラーのため次のユーザーにスキップ');
                        break 2;
                    }
                }
            }
            Log::info('##自動いいねが完了しました');
            $this->sendMail($system_manager_id, $twitter_user_id);
        }
        Log::info('=====================================================================');
        Log::info('AutoLike : 終了');
        Log::info('=====================================================================');
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
        Mail::to($user)->send(new CompleteLike($user, $twitter_user));
    }
    /**
     * APIを使用して、フィルターワードで指定されたワードでツイート検索を行う
     * @param $auto_like
     * @param $auto_like_list_quantity
     */
    private function fetchGetTweetListApi($auto_like, $auto_like_list_quantity)
    {
        Log::info('##API ツイートリスト取得開始');
        //APIに必要な変数の用意
        $count = self::API_REQUEST_RATE_PER_DAY / self::DO_API_PER_A_DAY / $auto_like_list_quantity;
        $query = $auto_like->filterWord->getMergedWordStringForQuery();
        Log::info('##いいねする数: ', [$count]);
        Log::info('##検索クエリ: ', [$query]);
        $token = $auto_like->twitterUser->token;
        $token_secret = $auto_like->twitterUser->token_secret;
        $param = [
            'q' => $query,
            'count' => $count,
            'result_type' => 'recent',
            'include_entities' => false,
        ];
        //API呼び出し
        $response_json = TwitterApi::useTwitterApi('GET', self::API_URL_SEARCH,
            $param, $token, $token_secret);
        Log::info('##API ツイートリスト取得完了');
        return $response_json;
    }
    /**
     * APIを使用して、いいねをする
     * @param $auto_like
     * @param $like_target_id
     */
    private function fetchLikeApi($auto_like, $like_target_id)
    {
        Log::debug('##API 自動いいね開始');
        //APIに必要な変数の用意
        $token = $auto_like->twitterUser->token;
        $token_secret = $auto_like->twitterUser->token_secret;
        $param = [
            'id' => $like_target_id,
            'include_entities' => false,
        ];
        //API呼び出し
        $response_json = TwitterApi::useTwitterApi('POST', self::API_URL_LIKE,
            $param, $token, $token_secret);
        Log::debug('##API 自動いいね完了');
        return $response_json;
    }
}
