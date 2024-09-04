<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResourcesController;
use App\Http\Controllers\ResourceTypeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StatusesController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\NoBrowserCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// Rutas no protegidass
Route::post("register", [AuthController::class, "register"])->name("register");
Route::post("login", [AuthController::class, "login"])->name("login");

Route::get("login", [AuthController::class, "login"])->name("login");

Route::middleware(['auth:api', NoBrowserCache::class])->group(function () {
//    Route::post("logout", [AuthController::class, "logout"])->name("endsession");

    // *********************************************** Citas ******************************************************
    Route::get('/appointments', [AppointmentsController::class, 'index'])->name("citas");
    Route::get('/appointments/index', [AppointmentsController::class, 'index'])->name("citas");
    Route::get('/appointments/ver/{id}', [AppointmentsController::class, 'show'])->name("appointments.show");

//    Route::post('/appointments', [AppointmentsController::class, 'store'])->name("appointments");
//    Route::put('/appointments/editar/{id}', [AppointmentsController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/eliminar/{id}', [AppointmentsController::class, 'destroy'])->name("appointments.destroy");

    // **************************************** GET Usuarios ******************************************************
    Route::get('/users', [UsersController::class, "index"])->name('usuarios.index');
    Route::get("/users/ver/{id}", [UsersController::class, "show"])->name("users.show");


    Route::delete("/users/eliminar/{id}", [UsersController::class, "destroy"]);

    // **************************************** GET Inventarios ******************************************************
    Route::get('/statuses', [StatusesController::class, 'index']);
    Route::get('/resourcesTypes', [ResourceTypeController::class, 'index']);
    Route::get('/room', [RoomController::class, 'index']);
    Route::get('/statuses', [StatusesController::class, 'index']);
    Route::get('/resources', [ResourcesController::class, 'index']);
});
