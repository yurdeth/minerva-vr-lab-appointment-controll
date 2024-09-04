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

// ********************************Rutas para rutas no definidas*************************************
Route::fallback(function () {
    return redirect()->route('iniciar_sesion');
});

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

/*Route::get('/departments', [DepartmentsController::class, 'index'])->name("departments");
Route::get('/careers/{id}', [CareersController::class, 'show'])->name("careers");
Route::get('/careers/', [CareersController::class, 'index'])->name("careers");*/

// ***************************************Rutas para usuarios*********************************************
Route::middleware(['auth', NoBrowserCache::class])->group(function () {

    Route::post("logout", [AuthController::class, "logout"])->name("logout");
    Route::get("logout", [AuthController::class, "logout"])->name("logout");

    Route::get('/home', function () {
        return view('home');
    })->name('HomeVR');

    Route::get('/profile', function () {
        return view('editUser');
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

    /*Código con la funcionalidad que no de error al abrir el informe de inventario*/
    Route::post('/insertar-inventario', [StatusesController::class, 'store'])->name("insertar-inventario");
    Route::post('/statuses/create', [StatusesController::class, 'store']);
    Route::post('/resourcesTypes/create', [ResourceTypeController::class, 'store']);
    Route::post('/room/create', [RoomController::class, 'store']);
    Route::post('/resources/create', [ResourcesController::class, 'store']);
});

// ***************************************Rutas para citas*********************************************
Route::middleware(['auth', NoBrowserCache::class])->group(function () {

    // Rutas API:
    Route::post('/citas', [AppointmentsController::class, 'store'])->name("appointments");

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
