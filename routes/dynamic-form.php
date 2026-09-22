<?php

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

Route::group(
    [
        'namespace' => 'App\Http\Controllers',
        'prefix' => 'dynamic_form/api/v1/client',
        'middleware' => ['verify.client'],
    ],
    function () {
        Route::group(['prefix' => 'form'], function () {
            Route::group(['prefix' => 'type/{id}'], function () {
                Route::get('', 'FormController@getForm')->name('get-dynamic-form');
                Route::get('diff/{diffId}', 'FormController@getFormDiff')->name('get-dynamic-form-diff');
            });
        });
    }
);


Route::group(
    [
        'namespace' => 'App\Http\Controllers',
        'prefix' => 'dynamic_form/api/v1/manage',
        // 'middleware' => ['jwt.client'],
    ],
    function () {
        Route::group(['prefix' => 'form/type/{tid}/group/{gid}/element'], function () {
            Route::post('', 'ManageController@createFromElement')->name('create-form-element');
        });
    }
);
