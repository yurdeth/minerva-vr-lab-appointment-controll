<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\EmailUniqueIgnoreCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Rules\OnlyUesMail;

use App\Services\ValidationService;
use App\Enum\ValidationTypeEnum;

class AuthController extends Controller {

    public function register(Request $request): JsonResponse {
        $validationService = new ValidationService($request, ValidationTypeEnum::REGISTER_USER);
        $response = $validationService->ValidateRequest();
        if ($response) {
            return $response;
        }

        $validate = Validator::make($request->all(), [
            "name" => "required|string",
            "email" => [
                "required",
                "string",
                "unique:users,email",
                new OnlyUesMail(),
                new EmailUniqueIgnoreCase("users", "email"),
            ],
            "password" => "required|string|confirmed|min:8",
            "career" => "required|exists:careers,id",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "message" => "Error en los datos",
                "error" => $validate->errors(),
                "success" => false,
            ]);
        }

        // Crear el usuario
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "roles_id" => '2',
            "password" => Hash::make($request->password),
            "career_id" => $request->career,
        ]);

        if (!$user) {
            return response()->json([
                "message" => "Error al crear el registro",
                "success" => false,
            ], 500);
        }

        Auth::login($user);
        $token = $user->createToken("token")->accessToken;

        return response()->json([
            "token" => $token,
            "token_type" => "Bearer",
            "redirect_to" => route("HomeVR"),
            "success" => true,
        ], 201);
    }

    public function login(Request $request): JsonResponse|RedirectResponse {
        // Campos esperados en la petición
        $credentials = $request->only('email', 'password');

        if(!$request->email && !$request->password){
            return redirect()->route("iniciarSesion");
        }

        if (!$request->email){
            return response()->json([
                "message" => "Por favor, ingrese su correo",
                "success" => false,
            ]);
        }

        if (!$request->password){
            return response()->json([
                "message" => "Por favor, ingrese su contraseña",
                "success" => false,
            ]);
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            Auth::login($user);

            // Crear un nuevo token
            $token = Auth::user()->createToken("token")->accessToken;

            // Redireccionamiento basado en el rol del usuario
            if (Auth::user()->roles_id == 1) {
                $route = "dashboard";
            } else {
                $route = "HomeVR";
            }

            return response()->json([
                "token" => $token,
                "token_type" => "Bearer",
                "redirect_to" => route($route),
                "success" => true,
            ], 201);

        }

        if (!$request->email || !$request->password) {
            return response()->json([
                "message" => "Faltan credenciales",
                "success" => false,
                "redirect_to" => route("login"),
            ], 401);
        }

        return response()->json([
            "message" => "Credenciales erróneas",
            "success" => false,
            "redirect_to" => route("login"),
        ], 401);
    }

    public function logout(Request $request): RedirectResponse {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route("inicio");
    }
}
