<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnfollowInspectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unfollow_inspects', function (Blueprint $table) {
            $table->string('twitter_id', 30);
            $table->unsignedInteger('twitter_user_id');
            $table->timestamps();
            $table->foreign('twitter_user_id')->references('id')->on('twitter_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unfollow_inspects');
    }
}
