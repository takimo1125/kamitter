<?php

namespace App\Http\Requests;

use App\FilterWord;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AddFilterWord extends FormRequest
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
        $type_rule = Rule::in(array_keys(FilterWord::TYPE));

        return [
            'type' => 'required|' . $type_rule,  // 'type' => 'required|in(1, 2)', となる
            'word' => 'max:50|required',
            'remove' => 'max:50',
        ];
    }

    public function attributes()
    {
        return [
          'type' => '条件タイプ',
          'word' => 'キーワード',
          'remove' => '除外ワード'
        ];
    }

    public function messages()
    {
        return [
          'type.required' => '条件タイプを選択してください。'
        ];
    }
}
