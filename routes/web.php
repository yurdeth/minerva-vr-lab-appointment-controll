<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatusesController;
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

    Route::get('/dashboard/inventario', function () {
        return view('inventory.inventory');
    })->name('inventario');

    Route::get('/dashboard/inventario/registrar', function () {
        return view('inventory.registerInventory');
    })->name('registrar-inventario');

    Route::get('/dashboard/inventario/ver/{id}', function () {
        return view('inventory.editInventory');
    })->name('editar-inventario');

    /*Código con la funcionalidad que no de error al abrir el informe de inventario*/
    Route::post('/insertar-inventario', [StatusesController::class, 'store'])->name("insertar-inventario");
});

// ***************************************Rutas para citas*********************************************
Route::middleware(['auth', NoBrowserCache::class])->group(function () {
    Route::get('/citas', function () {
        return view('appointments.appointments');
    })->name('citas-ver');

    Route::get('/citas/index', function () {
        return view('appointments.appointments');
    })->name('citas-ver-index');

    Route::get('/citas/nueva', function () {
        return view('appointments.addAppointment');
    })->name('agendar');

    Route::get('/citas/ver/{id}', function () {
        return view('appointments.editAppointments');
    })->name('citas-editar');

    Route::get('/export',[ExportController::class, 'export'])->name('export');
    Route::get('/citas/pdf', [AppointmentsController::class, 'pdf'])->name("pdf");
});

// ***************************************Rutas para admin*********************************************
Route::middleware(['auth', NoBrowserCache::class, RoleMiddleware::class . ':1'])->group(function () {

    Route::get('/dashboard', function () {
        return view('Administración.dashboard');
    })->name('dashboard');

    Route::get('/dashboard/usuarios', function () {
        return view('users');
    })->name('usuarios');

    Route::get('/usuarios/ver/{id}', function () {
        return view('editUser');
    })->name('usuarios-editar');

    Route::get('/dashboard/citas/', function () {
        $dashboard = true;
        return view('appointments.appointments', compact("dashboard"));
    })->name('citas_dashboard');

    Route::get('/dashboard/carreras', function () {
        return view('careers.careers');
    })->name('carreras');

    Route::get('/dashboard/carreras/nueva/', function () {
        return view('careers.add_career');
    })->name('carreras-agregar');

    Route::get('/dashboard/carreras/ver/{id}', function () {
        return view('careers.edit_career');
    })->name('carreras-ver');

    Route::get('/dashboard/departamentos/', function () {
        return view('careers.add_department');
    })->name('departamentos-agregar');

    Route::get('/dashboard/departamentos/ver/{id}', function () {
        return view('careers.edit_department');
    })->name('departamentos-ver');
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
    $user->career_id = '1';
    $user->roles_id = '1';
    $user->save();

    return response()->json([
        "message" => "Base de datos inicializada correctamente",
        "success" => true
    ]);
});
