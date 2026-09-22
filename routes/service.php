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
        'prefix' => 'dynamic_form/api/v1/service/',
        'middleware' => ['verify.client'],
    ],
    function () {
        Route::group(['prefix' => 'forms'], function () {
            Route::group(['prefix' => 'type/{id}'], function () {
                Route::get('', 'FormController@getForm')->name('form.get');
                Route::get('diff/{diffId}', 'FormController@getFormDiff')->name('form.diff');
                Route::post('contracts/create', 'ContractController@createDigitalContract')->name('contract.create');
            });
        });

        Route::group(['prefix' => 'contracts'], function () {
            Route::get('failed', 'ContractController@getFailedContracts')->name('contracts.failed');
            Route::post('recreate', 'ContractController@recreateContract')->name('contracts.recreate');
            Route::put('process/status', 'ContractController@updateContractProcess')->name('contracts.update-process');
        });
        
        Route::group(['prefix' => 'data'], function () {
            Route::get('sales-order', 'SalesOrderController@getSalesOrderById')->name('sales-order.get');
        });
    }
);
