<?php

use App\Http\Controllers\ContentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('api')->get('/clients', 'ClientController@list')->name('client.list');

Route::middleware('api')->get('/client', 'ClientController@getClient')->name('client.item');
Route::middleware('api')->post('/client', 'ClientController@store')->name('client.register');
Route::middleware('api')->delete('/client', 'ClientController@delete')->name('client.delete');

Route::group(['middleware' => ['api']], function() {
    // 記事一覧
    Route::get('/contents/{clientId?}', 'ContentController@index')->name('contents.list');

    // 記事新規登録
    Route::post('/contents/{clientId}', 'ContentController@store')->name('content.store');

    // 記事情報取得
    Route::get('/contents/{clientId}/{contentId}', 'ContentController@show')->name('content.show');

    // 記事更新
    Route::patch('/contents/{clientId}/{contentId}', 'ContentController@update')->name('content.update');

    // 記事削除
    Route::delete('/contents/{clientId}/{contentId}', 'ContentController@delete')->name('content.delete');

    // 自動保存記事の取得
    Route::get('/contents/autosave/{clientId}/{contentId}', 'ContentController@showAutosave')->name('content.show_autosave');

    // 記事の自動保存
    Route::post('/contents/autosave/{clientId}/{contentId}', 'ContentController@storeAutosave')->name('content.store_autosave');
});
