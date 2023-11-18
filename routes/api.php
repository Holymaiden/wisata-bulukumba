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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api'], function () {
    Route::post('/login', 'LoginController@login');
    Route::post('/register', 'LoginController@register');
});

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/wisatas', 'WisataController@getWisata');
    Route::get('/wisatas/{id}', 'WisataController@detailWisata');
    Route::get('/wisatas-populer', 'WisataController@getWisataPopuler');
    Route::get('/bellmand', 'WisataController@bellmandford');
    Route::get('/test', 'WisataController@test');

    Route::get('/suka', 'SukaController@getSuka');
    Route::get('/suka/user', 'SukaController@getSukaUser');
    Route::post('/suka', 'SukaController@createSuka');
    Route::delete('/suka/{id}', 'SukaController@deleteSuka');

    Route::get('/riwayat', 'RiwayatController@getRiwayat');
    Route::get('/riwayat/{id}', 'RiwayatController@detailRiwayat');
    Route::post('/riwayat', 'RiwayatController@createRiwayat');
    Route::put('/riwayat/{id}', 'RiwayatController@updateRiwayat');
    Route::delete('/riwayat/{id}', 'RiwayatController@deleteRiwayat');

    Route::get('/users/{id}', 'LoginController@getUser');
    Route::put('/users/{id}', 'LoginController@updateUser');

    Route::get('/komentar', 'KomentarController@getByWU');
    Route::post('/komentar', 'KomentarController@createKomentar');
});
