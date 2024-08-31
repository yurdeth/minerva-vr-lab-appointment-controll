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
    Route::get("/users/ver/{id}", [UsersController::class, "show"])->name("users.show");
    Route::put("/users/editar/{id}", [UsersController::class, "update"])->name("users.update");
    Route::delete("/users/{id}", [UsersController::class, "destroy"])->name("users.destroy");

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

    Route::post('statuses/create', [StatusesController::class, 'store']);
    Route::get('statuses', [StatusesController::class, 'index']);
    Route::get('resourcesTypes', [ResourceTypeController::class, 'index']);
    Route::post('resourcesTypes/create', [ResourceTypeController::class, 'store']);
    Route::get('/room', [RoomController::class, 'index']);
    Route::post('/room/create', [RoomController::class, 'store']);
    Route::get('/resources', [ResourcesController::class, 'index']);
    Route::post('/resources/create', [ResourcesController::class, 'store']);
});

// ***************************************Rutas para citas*********************************************
Route::middleware(['auth', NoBrowserCache::class])->group(function () {

    // Rutas API:
    Route::get('/appointments', [AppointmentsController::class, 'index'])->name("citas");
    Route::get('/appointments/index', [AppointmentsController::class, 'index'])->name("citas");
    Route::post('/citas', [AppointmentsController::class, 'store'])->name("appointments");
    Route::get('/appointments/ver/{id}', [AppointmentsController::class, 'show'])->name("appointments.show");
    Route::put('/appointments/editar/{id}', [AppointmentsController::class, 'update'])->name('appointments.update');
    Route::delete('/citas/eliminar/{id}', [AppointmentsController::class, 'destroy'])->name("appointments.destroy");

    Route::get('/citas', function () {
        return view('appointments');
    })->name('citas-ver');

    Route::get('/citas/index', function () {
        return view('appointments');
    })->name('citas-ver-index');

    Route::get('/citas/nueva', function () {
        return view('registro_cita');
    })->name('agendar');

    Route::get('/citas/ver/{id}', function () {
        return view('editAppointments');
    })->name('citas-editar');

    Route::get('/export',[ExportController::class, 'export'])->name('export');
    Route::get('/citas/pdf', [AppointmentsController::class, 'pdf'])->name("pdf");
    Route::get('/citas/horarios-disponibles', [AppointmentsController::class, 'AvailableSchedules'])->name("AvailableSchedules");
});

// ***************************************Rutas para admin*********************************************
Route::middleware(['auth', NoBrowserCache::class, RoleMiddleware::class . ':1'])->group(function () {
    // Retornar una respuesta del servidor:
    Route::get('/users', [UsersController::class, "index"])->name('usuarios.index');

    // Retornar una vista:
    Route::get('/dashboard', function () {
        return view('Administración.dashboard');
    })->name('dashboard');

    Route::get('/usuarios', function () {
        return view('users');
    })->name('usuarios');

    Route::get('/usuarios/ver/{id}', function () {
        return view('editUser');
    })->name('usuarios-editar');
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
