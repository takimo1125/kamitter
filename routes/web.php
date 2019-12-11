<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * ツイッター認証でTwitterと通信する際のルート
 */
//認証ページにリダイレクトする
Route::get('auth/twitter/oauth', 'TwitterAuthController@oauth')->name('twitter.oauth');
//コールバックの処理
Route::get('auth/twitter/callback', 'TwitterAuthController@handleProviderCallback')->name('twitter.callback');

// APIのURL以外のリクエストに対してはindexテンプレートを返す
Route::get('/{any?}', function () {
    return view('index');
})->where('any', '.+');
