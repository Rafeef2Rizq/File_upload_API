<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);


Route::middleware('auth:sanctum')->post('/logout',[AuthController::class,'logout']);

Route::middleware('auth:sanctum')->post('/upload',[FileController::class,'upload']);
Route::middleware('auth:sanctum')->get('/files',[FileController::class,'index']);
Route::middleware('auth:sanctum')->post('/destroy/{file}',[FileController::class,'destroy']);

Route::middleware('auth:sanctum')->get('/download/{file}',[FileController::class,'download']);
