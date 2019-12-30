<?php

namespace App\Http\Requests;

use App\Rules\ApiTwitterUserExist;
use App\Rules\ApiTwitterUserProtected;
use Illuminate\Foundation\Http\FormRequest;

class AddFollowTarget extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //apiTwiterUserExistは入力されたscreenのユーザーが存在するかチェックする
            ////apiTwiterUserExistは入力されたscreenのユーザーが非公開ユーザーかチェックする
            'target' => ["required", "max:15", "regex:/^[a-zA-Z0-9_]+$/i", new ApiTwitterUserExist, new ApiTwitterUserProtected],
            'filter_word_id' => 'required',
        ];
    }
}
