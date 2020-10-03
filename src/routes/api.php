<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->get('/clients', 'ClientController@list')->name('client.list');

Route::middleware('api')->get('/client', 'ClientController@getClient')->name('client.item');
Route::middleware('api')->post('/client', 'ClientController@store')->name('client.register');
Route::middleware('api')->delete('/client', 'ClientController@delete')->name('client.delete');

Route::middleware('api')->get('/content/{id}')->name('content.read');
Route::middleware('api')->post('/content/{id}', 'ContentController@store')->name('content.store');
Route::middleware('api')->patch('/content/{id}')->name('content.update');
Route::middleware('api')->delete('/content/{id}')->name('content.delete');

Route::middleware('api')->get('/content/autosave/{clientId}/{contentId}', 'ContentController@fetchAutosave')->name('content.get_autosave');
Route::middleware('api')->post('/content/autosave/{clientId}/{contentId}', 'ContentController@autosave')->name('content.autosave');
