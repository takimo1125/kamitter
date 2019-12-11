<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TwitterUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = DB::table('users')->first();
        DB::table('twitter_users')->insert([
            'id' => 1,
            'user_id' => $user->id,
            'token' => 'aaaa',
            'token_secret' => 'bbb',
            'screen' => 'cccc',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
