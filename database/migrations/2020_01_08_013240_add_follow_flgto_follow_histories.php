<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFollowFlgtoFollowHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('follow_histories', function (Blueprint $table) {
            //
            $table->boolean('follow_flg')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('follow_histories', function (Blueprint $table) {
            //
            $table->dropColumn('follow_flg');
        });
    }
}
