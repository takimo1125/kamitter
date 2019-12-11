<?php

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FilterWordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = DB::table('users')->first();
        DB::table('fillter_words')->insert([
            'id' => 1,
            'user_id' => $user->id,
            'type' => 1,
            'word' => 'bbb',
            'remove' => 'cccc',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
