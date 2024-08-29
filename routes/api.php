<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResourcesController;
use App\Http\Controllers\ResourceTypeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StatusesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// Rutas no protegidass
Route::post("register", [AuthController::class, "register"])->name("register");
Route::post("login", [AuthController::class, "login"])->name("login");

Route::middleware('auth:api')->group(function () {
    Route::post("logout", [AuthController::class, "logout"])->name("endsession");

    Route::get('/appointments', AppointmentsController::class . '@index');
    Route::post('/appointments', AppointmentsController::class . '@store')->name('appointments.store');
    // Add other routes that require authentication here
});
