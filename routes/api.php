<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\DB;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['simple.auth', 'simple.author:teacher']], function () {
    Route::post('tclass', [TeacherController::class, 'getClasses']);
    Route::post('classDetail', [TeacherController::class, 'getClassDetail']);
    Route::post('createDeadline', [TeacherController::class, 'createDealine']);
});


Route::group(['middleware' => ['simple.auth', 'simple.author:student']], function () {
    Route::post('getDeadlines', [StudentController::class, 'getDeadlines']);
    Route::post('sclass', [StudentController::class, 'getClasses']);
    Route::post('updateFreetimes', [StudentController::class, 'updateFreetimes']);
    Route::post('getFreetimes', [StudentController::class, 'getFreetimes']);
});