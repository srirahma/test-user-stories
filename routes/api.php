<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::post('/request', [UserController::class, 'request']);
Route::post('/status', [UserController::class, 'status_action']);
Route::get('/list-requestor', [UserController::class, 'get_request']);
Route::get('/list-friends', [UserController::class, 'get_friends']);
Route::get('/list-all-friend', [UserController::class, 'get_all_friend']);
