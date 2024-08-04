<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\Exceptions\MissingScopeException;

class BearerTokenMiddleware {
    protected $tokenRepository;

    public function __construct(TokenRepository $tokenRepository) {
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response {
        $bearerToken = $request->header('Authorization');

        if (!$bearerToken) {
            return response()->json([
                "message" => "No se ha proporcionado un token de acceso",
                "success" => false,
            ], 401);
        }

        $tokenParts = explode(" ", $bearerToken);
        if (count($tokenParts) != 2) {
            return response()->json([
                "message" => "El formato del token de acceso es inválido",
                "success" => false,
            ], 401);
        }
        $token = $tokenParts[1];

        $isValid = Auth::guard('api')->validate(['request' => $request, 'token' => $token]);

        if (!$isValid) {
            return response()->json([
                "message" => "Token de acceso inválido",
                "success" => false,
            ], 401);
        }

        $request->attributes->set('token', $token);

        return $next($request);
    }
}
