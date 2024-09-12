<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Principal;
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
Route::get('/Users/GetAllUsers', [Principal::class, 'getAllUsers']);
Route::get('/Books/GetAllBooks', [Principal::class, 'getAllBooks']);
Route::post('/Users/CreateNewUser', [Principal::class, 'createNewUser']);