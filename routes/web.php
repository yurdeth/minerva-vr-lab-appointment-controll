<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\StatusesController;
use App\Http\Middleware\CheckKeyAccess;
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
    return redirect()->route('iniciarSesion');
});

// ***************************************Rutas públicas*********************************************

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('HomeVR');
    }
    return view('inicio');
})->name('inicio.index');

Route::get('/inicio', function () {
    if (Auth::check()) {
        return redirect()->route('HomeVR');
    }
    return view('inicio');
})->name('inicio');

Route::get('/actualizar-informacion', function () {
    if (Auth::check()) {
        return redirect()->route('HomeVR');
    }
    return view("updateInformation");
})->name('actualizar-informacion');

Route::get('/contactar-administrador', function () {
    if (Auth::check()) {
        return redirect()->route('HomeVR');
    }
    return view("contactAdmin");
})->name('contactar-administrador');

Route::post('/enviar-solicitud', [NotificationsController::class, 'store'])->name('enviar-solicitud');

Route::get('/iniciar-sesion', function () {
    if (Auth::check()) {
        return redirect()->route('HomeVR');
    }
    return view("iniciarSesion");
})->name('iniciarSesion');

Route::post('/signin', [AuthController::class, 'login'])->name("signin");
Route::post('/signup', [AuthController::class, 'register'])->name("signup");

Route::get('/get-key', [ApiController::class, 'getKey'])
    ->name('get-key')
    ->middleware(CheckKeyAccess::class); // <- Este middleware limita el acceso a la ruta, pues el usuario solo puede verla dos veces

// ***************************************Rutas para usuarios*********************************************
Route::middleware(['auth', NoBrowserCache::class])->group(function () {


    //Pruebas de rutas logout con nombres repetidos
    Route::post("logout", [AuthController::class, "logout"])->name("logout.post");
    Route::get("logout", [AuthController::class, "logout"])->name("logout");

    Route::get('/home', function () {
        return view('home');
    })->name('HomeVR');

    Route::get('/profile', function () {
        return view('users.editUser');
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

    Route::get('/export', [ExportController::class, 'export'])->name('export');
    Route::get('/citas/pdf', [AppointmentsController::class, 'pdf'])->name("pdf");
});

// ***************************************Rutas para admin*********************************************
Route::middleware(['auth', NoBrowserCache::class, RoleMiddleware::class . ':1'])->group(function () {

    Route::get('/dashboard', function () {
        return view('administration.dashboard');
    })->name('dashboard');

    Route::get('/dashboard/usuarios', function () {
        return view('users.users');
    })->name('usuarios');

    Route::get('/usuarios/ver/{id}', function () {
        return view('users.editUser');
    })->name('usuarios-editar');

    Route::get('/dashboard/citas/', function () {
        $dashboard = true;
        return view('appointments.appointments', compact("dashboard"));
    })->name('citas_dashboard');

    Route::get('/dashboard/carreras', function () {
        return view('careers.careers');
    })->name('carreras');

    Route::get('/dashboard/carreras/nueva/', function () {
        return view('careers.addCareer');
    })->name('carreras-agregar');

    Route::get('/dashboard/carreras/ver/{id}', function () {
        return view('careers.editCareer');
    })->name('carreras-ver');

    Route::get('/dashboard/departamentos/', function () {
        return view('careers.addDepartment');
    })->name('departamentos-agregar');

    Route::get('/dashboard/departamentos/ver/{id}', function () {
        return view('careers.editDepartment');
    })->name('departamentos-ver');

    Route::get('/dashboard/notificaciones', function () {
        return view('notifications.notifications');
    })->name('notificaciones');

    Route::get('/dashboard/notificaciones/ver/{id}', function () {
        return view('notifications.viewNotification');
    })->name('notificaciones-ver');

    Route::get('/dashboard/notificaciones/claves-de-acceso', function () {
        return view('notifications.accessPasswordRequesting');
    })->name('solicitud-clave-default');

    Route::get('/dashboard/notificaciones/recuperacion-de-clave', function () {
        return view('notifications.recoveringPasswordRequesting');
    })->name('solicitud-recuperar-clave');
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
