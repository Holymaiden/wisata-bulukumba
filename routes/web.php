<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['prefix' => '',  'namespace' => 'App\Http\Controllers\Admin',  'middleware' => ['auth']], function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::group(['prefix' => ''], function () {
            Route::get('/', 'DashboardController@index')->name('dashboard');
        });

        Route::group(['prefix' => '/riwayats'], function () {
            Route::get('/', 'RiwayatController@index')->name('riwayats');
            Route::get('/data', 'RiwayatController@data')->name('riwayats.data');
            Route::post('/store', 'RiwayatController@store')->name('riwayats.store');
            Route::get('/{id}/edit', 'RiwayatController@show')->name('riwayats.show');
            Route::put('/{id}', 'RiwayatController@update')->name('riwayats.update');
            Route::delete('/{id}', 'RiwayatController@destroy')->name('riwayats.delete');
        });

        Route::group(['prefix' => '/sukas'], function () {
            Route::get('/', 'SukaController@index')->name('sukas');
            Route::get('/data', 'SukaController@data')->name('sukas.data');
            Route::post('/store', 'SukaController@store')->name('sukas.store');
            Route::get('/{id}/edit', 'SukaController@show')->name('sukas.show');
            Route::put('/{id}', 'SukaController@update')->name('sukas.update');
            Route::delete('/{id}', 'SukaController@destroy')->name('sukas.delete');
        });

        Route::group(['prefix' => '/users'], function () {
            Route::get('/', 'UserController@index')->name('users');
            Route::get('/data', 'UserController@data')->name('users.data');
            Route::post('/store', 'UserController@store')->name('users.store');
            Route::get('/{id}/edit', 'UserController@show')->name('users.show');
            Route::put('/{id}', 'UserController@update')->name('users.update');
            Route::delete('/{id}', 'UserController@destroy')->name('users.delete');
        });

        Route::group(['prefix' => '/wisatas'], function () {
            Route::get('/', 'WisataController@index')->name('wisatas');
            Route::get('/data', 'WisataController@data')->name('wisatas.data');
            Route::post('/store', 'WisataController@store')->name('wisatas.store');
            Route::get('/{id}/edit', 'WisataController@show')->name('wisatas.show');
            Route::put('/{id}', 'WisataController@update')->name('wisatas.update');
            Route::delete('/{id}', 'WisataController@destroy')->name('wisatas.delete');
        });

        Route::group(['prefix' => '/komentar'], function () {
            Route::get('/', 'KomentarController@index')->name('komentar');
            Route::get('/data', 'KomentarController@data')->name('komentar.data');
        });

        Route::group(['prefix' => '/profile'], function () {
            Route::get('/', 'ProfileController@index')->name('profile');
        });
    });
});

Route::get('/cc', function () {
    Artisan::call('optimize:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
});

require __DIR__ . '/auth.php';
