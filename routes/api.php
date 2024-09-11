<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CareersController;
use App\Http\Controllers\DepartmentsController;
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

Route::get('/departments', [DepartmentsController::class, 'index'])->name("departments");
Route::get('/careers/{id}', [CareersController::class, 'show'])->name("careers");
Route::get('/careers/', [CareersController::class, 'index'])->name("careers");

// Rutas no protegidass
Route::post("register", [AuthController::class, "register"])->name("register");
Route::post("login", [AuthController::class, "login"])->name("login");

Route::get("login", [AuthController::class, "login"])->name("login");

Route::middleware(['auth:api', NoBrowserCache::class])->group(function () {

    // *********************************************** Citas ******************************************************
    Route::get('/appointments', [AppointmentsController::class, 'index'])->name("appointments.index");
    Route::get('/appointments/ver/{id}', [AppointmentsController::class, 'show'])->name("appointments.show");
    Route::post('/appointments', [AppointmentsController::class, 'store'])->name("appointments.create");
    Route::put('/appointments/editar/{id}', [AppointmentsController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/eliminar/{id}', [AppointmentsController::class, 'destroy'])->name("appointments.destroy");
    Route::get('/appointments/available', [AppointmentsController::class, 'AvailableSchedules'])->name("appointments.available");

    // **************************************** GET Usuarios ******************************************************
    Route::get('/users', [UsersController::class, "index"])->name('usuarios.index');
    Route::get("/users/ver/{id}", [UsersController::class, "show"])->name("users.show");
    Route::put("/users/editar/{id}", [UsersController::class, "update"])->name("users.update");
    Route::delete("/users/eliminar/{id}", [UsersController::class, "destroy"])->name("users.destroy");

    // **************************************** GET Inventarios ******************************************************
    Route::get('/statuses', [StatusesController::class, 'index']);
    Route::post('/statuses/create', [StatusesController::class, 'store']);

    Route::get('/resourcesTypes', [ResourceTypeController::class, 'index']);
    Route::post('/resourcesTypes/create', [ResourceTypeController::class, 'store']);

    Route::get('/room', [RoomController::class, 'index']);
    Route::post('/room/create', [RoomController::class, 'store']);

    Route::get('/resources', [ResourcesController::class, 'index']);
    Route::get('/resources/ver/{id}', [ResourcesController::class, 'show']);
    Route::post('/resources/create', [ResourcesController::class, 'store']);
    Route::put('/resources/editar/{id}', [ResourcesController::class, 'update']);
    Route::delete('/resources/eliminar/{id}', [ResourcesController::class, 'destroy']);

    Route::post('/careers/nueva', [CareersController::class, 'store'])->name("careers.store");
    Route::get('/careers/ver/{id}', [CareersController::class, 'getCareerData'])->name("careers.getCareerData");
    Route::put('/careers/editar/{id}', [CareersController::class, 'update'])->name("careers.update");
    Route::delete('/careers/eliminar/{id}', [CareersController::class, 'destroy'])->name("careers.destroy");

    Route::post('/departments/nuevo', [DepartmentsController::class, 'store'])->name("departments.store");
});
