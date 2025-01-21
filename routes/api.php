<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/users', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('/register',[RegisterController::class,'register']);
    Route::post('/login',[LoginController::class,'login']);
    Route::post('/logout', [LogoutController::class,'logout'])->middleware('auth:sanctum');
});

Route::prefix('groups')->middleware('auth:sanctum')->group(function () {
    Route::get('/',[GroupController::class,'index']);
    Route::post('/',[GroupController::class,'store']);
    Route::patch('/{group}',[GroupController::class,'update']);
    Route::get('/{group}',[GroupController::class,'show']);
    Route::delete('/{group}',[GroupController::class,'destroy']);

});
Route::prefix('/files')->middleware('auth:sanctum')->group(function () {
    Route::post('/',[FileController::class,'store']);
    Route::get('/{file}/versions',[FileController::class,'getVersions']);
    Route::patch('/{file}/versions/{version}',[FileController::class,'setVersion']);
    Route::patch('/lock',[FileController::class,'lock']);
    Route::patch('/unlock',[FileController::class,'unlock']);
    Route::get('/{file}',[FileController::class,'show']);
    Route::patch('/{file}',[FileController::class,'update']);
    Route::delete('/{file}',[FileController::class,'destroy']);
    
});


Route::prefix('invitations')->middleware('auth:sanctum')->group(function () {
    Route::get('/',[InvitationController::class,'index']);
    Route::get('/users/{group}',[InvitationController::class,'getAllowedUsers']);
    Route::post('/',[InvitationController::class,'store']);
    Route::post('/{invitation}/accept',[InvitationController::class,'accept']);
    Route::post('/{invitation}/reject',[InvitationController::class,'reject']);
    Route::delete('/{invitation}',[InvitationController::class,'destroy']);

});

Route::prefix('reports')->middleware('auth:sanctum')->group(function () {
    Route::get('/file/{file}',[ReportController::class,'getFileReport']);
    Route::get('/group/{group}',[ReportController::class,'getUserReport']);
});
Route::prefix('notifications')->middleware('auth:sanctum')->group(function () {
    Route::get('/',[NotificationController::class,'index']);
    Route::get('/unread',[NotificationController::class,'unread']);
    Route::post('/set-all-read',[NotificationController::class,'markAsRead']);
});