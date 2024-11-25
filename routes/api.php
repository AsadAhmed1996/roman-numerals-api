<?php

use App\Http\Controllers\ConversionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('convert', [ConversionController::class, 'convertInteger']);
Route::get('recent-conversions', [ConversionController::class, 'listRecentConversions']);
Route::get('top-conversions', [ConversionController::class, 'listTop10Conversions']);
