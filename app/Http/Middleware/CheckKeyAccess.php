<?php

namespace App\Http\Middleware;

use Closure;

class CheckKeyAccess {
    public function handle($request, Closure $next) {
        // Nombre de la clave de sesión para el contador
        $key = 'key_access_count';

        // Obtener el contador de la sesión del usuario
        $count = $request->session()->get($key, 0);

        // Incrementar el contador
        $count++;

        // Actualizar el contador en la sesión del usuario
        $request->session()->put($key, $count);

        // Limitar el acceso a dos veces
        if ($count > 2) {
            abort(403, 'Forbidden.');
        }

        return $next($request);
    }
}
