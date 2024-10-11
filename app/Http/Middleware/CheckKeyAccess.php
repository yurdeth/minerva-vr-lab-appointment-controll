<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class CheckKeyAccess {
    public function handle($request, Closure $next) {

        // Obtener randKey del header de la solicitud
        $randKeyFromHeader = $request->header('randKey');

        // Obtener randKey de la sesión del usuario
        $randKeyFromSession = $request->session()->get('randKey');

        // Verificar la validez de randKey
        if (!$randKeyFromSession) {
            abort(403, 'Forbidden.'); //<- Este error salta cuando no hay una sesión activa
        }

        if ($randKeyFromSession !== $randKeyFromHeader) {
            abort(403, 'Forbidden.'); //<- Este error salta cuando la sesión no coincide con la solicitud
        }

        // Actualizar randKey en la sesión
        $randKey = bin2hex(random_bytes(128));
        Session::put('randKey', $randKey);

        return $next($request);
    }
}
