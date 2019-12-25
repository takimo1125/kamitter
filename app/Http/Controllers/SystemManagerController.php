<?php
namespace App\Http\Controllers;
use App\SystemManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditSystemManager;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
/**
 * 自動サービスのステータス変更、ステータス取得を行う
 * Class SystemManagerController
 */
class SystemManagerController extends Controller
{
    use AuthenticatesUsers;
    public function __construct()
    {
        // Controllerに認証を適応
        $this->middleware('auth');
    }
    /**
     * 指定されたサービスを稼働状態に変更する
     * @param EditSystemManager $request
     */
    public function run(EditSystemManager $request)
    {
        $twitter_user_id = session()->get('twitter_id');
        $system_manager = SystemManager::where('twitter_user_id', $twitter_user_id)->first();
        if (is_null($system_manager)) {
            abort(404);
        }
        switch ($request->type) {
            case 1:
                $system_manager->auto_follow_status = 2;
                break;
            case 2:
                $system_manager->auto_unfollow_status = 2;
                break;
            case 3:
                $system_manager->auto_like_status = 2;
                break;
            case 4:
                $system_manager->auto_tweet_status = 2;
                break;
        }
        if ($request->type === 1) {
            Artisan::call('auto:follow');
        }
        $system_manager->save();
        return response($system_manager, 200);
    }
    /**
     * 指定されたサービスを停止する
     * @param EditSystemManager $request
     */
    public function stop(EditSystemManager $request)
    {
        $twitter_user_id = session()->get('twitter_id');
        $system_manager = SystemManager::where('twitter_user_id', $twitter_user_id)->first();
        if (is_null($system_manager)) {
            abort(404);
        }
        switch ($request->type) {
            case 1:
                $system_manager->auto_follow_status = 1;
                break;
            case 2:
                $system_manager->auto_unfollow_status = 1;
                break;
            case 3:
                $system_manager->auto_like_status = 1;
                break;
            case 4:
                $system_manager->auto_tweet_status = 1;
                break;
        }
        $system_manager->save();
        return response($system_manager, 200);
    }
    /**
     * TwitterUserが利用しているSystemManagerを返す
     */
    public function show()
    {
        $twitter_user_id = session()->get('twitter_id');
        $system_manager = SystemManager::where('twitter_user_id', $twitter_user_id)->first();
        if (is_null($system_manager)) {
            abort(404);
        }
        return response($system_manager, 200);
    }
}
