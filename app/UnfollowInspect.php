<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * アクティブユーザー検査に関するモデル
 * Class UnfollowInspect
 */
class UnfollowInspect extends Model
{
    //
    protected $fillable = [
      'twitter_user_id', 'twitter_id',
    ];
    protected $casts = [
        'twitter_user_id' => 'integer',
        'id' => 'integer'
    ];
}
