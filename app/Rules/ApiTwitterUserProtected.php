<?php

namespace App\Rules;

use App\TwitterUser;
use App\Http\Components\TwitterApi;
use Illuminate\Contracts\Validation\Rule;

class ApiTwitterUserProtected implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
       /**
        * 指定のscreen_nameのツイッターユーザーが公開ユーザかをチェック
        * 存在していればtrueを返す、存在しなければfalseを返す
        */
        $twitter_user_id = session()->get('twitter_id');
        $twitter_user = TwitterUser::where('id', $twitter_user_id)->with('systemManagers')->first();
        if( is_null($twitter_user)){
            return false;
        }
        $api_result = TwitterApi::getUsersShow($twitter_user, $value);
        info('result', [$api_result]);
        //ツイッターユーザーが公開ユーザか
        if (property_exists($api_result, 'protected')) {
            if($api_result->protected){
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '指定のTwitterユーザーは非公開ユーザーなのでターゲット名に登録できません。';
    }
}
