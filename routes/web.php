<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CareersController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\StatusesController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\NoBrowserCache;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\ExportController;

// ***************************************Rutas públicas*********************************************
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('HomeVR');
    }
    return view('inicio');
})->name('inicio');

Route::get('/inicio', function () {
    if (Auth::check()) {
        return redirect()->route('HomeVR');
    }
    return view('inicio');
})->name('inicio');

Route::get('/registrarse', function () {
    if (Auth::check()) {
        return redirect()->route('HomeVR');
    }
    return view("registrarse");
})->name('registrarse');

Route::get('/iniciar_sesion', function () {
    if (Auth::check()) {
        return redirect()->route('HomeVR');
    }
    return view("iniciar_sesion");
})->name('iniciar_sesion');

Route::post('/signin', [AuthController::class, 'login'])->name("signin");
Route::post('/signup', [AuthController::class, 'register'])->name("signup");

Route::get('/departments', [DepartmentsController::class, 'index'])->name("departments");
Route::get('/careers/{id}', [CareersController::class, 'show'])->name("careers");
Route::get('/careers/', [CareersController::class, 'index'])->name("careers");

// ***************************************Rutas para usuarios*********************************************
Route::middleware(['auth', NoBrowserCache::class])->group(function () {

    Route::post("logout", [AuthController::class, "logout"])->name("logout");
    Route::get("logout", [AuthController::class, "logout"])->name("logout");
    Route::get("/usuarios/ver/{id}", [UsersController::class, "show"])->name("users.show");
    Route::put("/usuarios/editar/{id}", [UsersController::class, "update"])->name("users.update");
    Route::delete("/usuarios/{id}", [UsersController::class, "destroy"])->name("users.destroy");

    Route::get('/home', function () {
        return view('home');
    })->name('HomeVR');

    Route::get('/profile', function () {
        return view('edit_user');
    })->name('profile');
});

// ***************************************Rutas para inventarios*********************************************
Route::middleware(['auth', NoBrowserCache::class, RoleMiddleware::class . ':1'])->group(function () {
    Route::get('/inventario', function () {
        return view('inventario');
    })->name('inventario');

    Route::get('/registro-inventario', function () {
        return view('registro-inventario');
    })->name('registro-inventario');

    Route::get('/statuses', [StatusesController::class, 'index'])->name("statuses");
    /*Código con la funcionalidad que no de error al abrir el informe de inventario*/
    Route::post('/insertar-inventario', [StatusesController::class, 'store'])->name("insertar-inventario");
});

// ***************************************Rutas para citas*********************************************
Route::middleware(['auth', NoBrowserCache::class])->group(function () {

    // Rutas API:
    Route::get('/citas', [AppointmentsController::class, 'index'])->name("citas");
    Route::get('/citas/index', [AppointmentsController::class, 'index'])->name("citas");
    Route::post('/citas', [AppointmentsController::class, 'store'])->name("appointments");
    Route::get('/citas/ver/{id}', [AppointmentsController::class, 'show'])->name("appointments.show");
    Route::put('/citas/editar/{id}', [AppointmentsController::class, 'update'])->name('appointments.update');
    Route::delete('/citas/eliminar/{id}', [AppointmentsController::class, 'destroy'])->name("appointments.destroy");

    Route::get('/citas/nueva', function () {
        return view('registro_cita');
    })->name('agendar');

    Route::get('/citas/editar', function () {
        return view('citas-editar');
    })->name('citas-editar');

    Route::get('/export',[ExportController::class, 'export'])->name('export');
    Route::get('/citas/pdf', [AppointmentsController::class, 'pdf'])->name("pdf");
    Route::get('/citas/horarios-disponibles', [AppointmentsController::class, 'AvailableSchedules'])->name("AvailableSchedules");
});

// ***************************************Rutas para admin*********************************************
Route::middleware(['auth', NoBrowserCache::class, RoleMiddleware::class . ':1'])->group(function () {
    Route::get('/usuarios', [UsersController::class, "index"])->name('usuarios');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// ***************************************Iniciar credenciales admin*********************************************
Route::get("init", function () {

    // Buscar el nombre "admin", o el id 1:
    $rol = DB::table('users')->where('roles_id', 1)->first();
    if ($rol) {
        return response()->json([
            "message" => "Base de datos ya inicializada",
            "success" => false
        ]);
    }

    $user = new User();
    $user->name = "admin";
    $user->email = "admin@admin.com";
    $user->password = Hash::make(env("ADMIN_PASSWORD"));
    $user->career_id = '3';
    $user->roles_id = '1';
    $user->save();

    return response()->json([
        "message" => "Base de datos inicializada correctamente",
        "success" => true
    ]);
});
