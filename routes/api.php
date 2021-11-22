<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
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

Route::post('/login', [ApiController::class, 'login_user']);
Route::get('/authenticated', [ApiController::class, 'auth_users']);

Route::post('/register',[ApiController::class, 'create_user']);
Route::get('/list',[ApiController::class, 'listUsers']);
Route::post('/message', [ApiController::class, 'conversation']);
Route::get('/collect_message/{sender}/{receiver}', [ApiController::class, 'show_message']);
