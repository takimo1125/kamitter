<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * アンフォローターゲットに関するモデル
 * Class UnfollowTarget
 */
class UnfollowTarget extends Model
{
    protected $casts = [
        'twitter_user_id' => 'integer',
        'id' => 'integer'
    ];
}
