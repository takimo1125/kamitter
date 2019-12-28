<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 検索キーワードに使用するモデル
 * Class FilterWord
 */
class FilterWord extends Model
{
    const AND = 1;
    const OR = 2;
    const TYPE = [
        1 => ['label' => '次のワードを含む'],
        2 => ['label' => 'いずれかのワードを含む'],
    ];
    protected $appends = [
        'type_label', 'merged_word'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'type' => 'integer'
    ];
    /**
     * リレーションシップ - usersテーブル
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    /**
     * リレーションシップ - automatic_likesテーブル
     */
    public function automaticLikes()
    {
        return $this->hasMany('App\AutomaticLike', 'filter_word_id');
    }
    /**
     * アクセサ - type_label
     */
    public function getTypeLabelAttribute()
    {
        $type = $this->attributes['type'];
        if (!isset(self::TYPE[$type])) {
            return '';
        }
        return self::TYPE[$type]['label'];
    }
    /**
     * 登録されたワードと除外ワードを1つの文字列にした形式で返す
     * アクセサ - merged_word
     */
    public function getMergedWordAttribute()
    {
        $type = $this->attributes['type'];
        if (!isset(self::TYPE[$type])) {
            return '';
        }
        $type_string = self::TYPE[$type]['label'];
        $word = $this->attributes['word'];
        $remove = $this->attributes['remove'];
        return "$type_string \n [$word] \n\n 除外ワード\n[$remove]";
    }
    /**
     * 登録されたワードと除外ワードを
     * TwitterAPIでサーチするためのの文字列にして返す
     */
    public function getMergedWordStringForQuery()
    {
        $type = $this->attributes['type'];
        $word = $this->attributes['word'];
        $str_word = ((int)$type === self::OR) ? str_replace(" ", " OR ", $word) : $word;
        $remove = (!empty($remove)) ? $this->generateRemoveString($this->attributes['remove']) : "";
        return $str_word . $remove . ' OR @z_zz__zz1928 -filter:retweets lang:ja';
    }
    /**
     * 除外ワードを 文字列 文字列 文字列の形に変換して返す
     * @param $word
     */
    private function generateRemoveString($word)
    {
        $exploded_words = explode(" ", $word);
        $remove_string = '';
        foreach ($exploded_words as $exploded_word) {
            $remove_string = $remove_string . ' -"' . $exploded_word . '"';
        }
        return $remove_string;
    }
}
