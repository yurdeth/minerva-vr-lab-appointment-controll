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
Route::get("login", [AuthController::class, "signin"])->name("api_signin");
Route::get("register", [AuthController::class, "signup"])->name("api_signup");
Route::post('statuses/create', [StatusesController::class, 'store']);
Route::get('statuses', [StatusesController::class, 'index']);
Route::get('resourcesTypes', [ResourceTypeController::class, 'index']);
Route::post('resourcesTypes/create', [ResourceTypeController::class, 'store']);
Route::get('/room', [RoomController::class, 'index']);
Route::post('/room/create', [RoomController::class, 'store']);
Route::get('/resources', [ResourcesController::class, 'index']);
Route::post('/resources/create', [ResourcesController::class, 'store']);

Route::middleware('auth:api')->group(function () {
    Route::post("logout", [AuthController::class, "logout"])->name("endsession");

    Route::get('/appointments', AppointmentsController::class . '@index');
    Route::post('/appointments', AppointmentsController::class . '@store')->name('appointments.store');
    // Add other routes that require authentication here
});
