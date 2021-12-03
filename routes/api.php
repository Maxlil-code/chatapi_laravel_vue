<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactsController;
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


// Protected Routes

Route::group(['middleware'=>['auth:sanctum']], function (){
    Route::get('/list',[ApiController::class, 'listUsers']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/authenticated', [ApiController::class, 'auth_users']);
    Route::get('/conversations/{id}',[ApiController::class, 'sent_received_messages']);
});

// Public Routes
Route::post('/login', [AuthController::class, 'login_user']);
Route::post('/register',[AuthController::class, 'register']);


Route::post('/message', [ApiController::class, 'conversation']);
Route::post('/send', [ContactsController::class, 'send']);
Route::get('/collect_message/{sender}/{receiver}', [ApiController::class, 'show_message']);
