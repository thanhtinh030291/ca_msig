<?php

use Illuminate\Http\Request;

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

Route::post('login', 'Api\AccountManagementController@login');
Route::group([

    'middleware' => ['api'],

], function ($router) {
    Route::post('requestSign', 'Api\UncSignController@requestSign');
    Route::get('/panorama/{mem_ref_no}',  'Api\PanoramaController@panorama')->name('panorama');
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('get/{id}', 'Api\TestController@index');