<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * アンフォロー履歴に関するモデル
 * Class UnfollowHistory
 */
class UnfollowHistory extends Model
{
    //
    protected $casts = [
        'id' => 'integer',
        'twitter_user_id' => 'integer'
    ];
}
