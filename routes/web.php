<?php

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
    return "welcome";
});

// Temporary sandbox demo page for Playwright E2E POC. No database or
// external service dependencies — isolated from real application routes.
Route::get('/e2e-demo', function () {
    return view('e2e-demo');
})->name('e2e-demo');
