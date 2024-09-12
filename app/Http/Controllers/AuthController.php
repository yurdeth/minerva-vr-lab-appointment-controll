<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\EmailUniqueIgnoreCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Rules\OnlyUesMail;

class AuthController extends Controller {
    public function signin() {
        return redirect()->route('iniciarSesion');
    }

    public function signup() {
        return redirect()->route('registrarse');
    }

    public function register(Request $request) {
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

    public function login(Request $request) {
        // Campos esperados en la petición
        $credentials = $request->only('email', 'password');

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

        if(!$request->email || !$request->password) {
            return redirect()->route('iniciarSesion')->with('error', 'Por favor, ingrese sus credenciales.');
        }

        return response()->json([
            "message" => "Credenciales erróneas",
            "success" => false,
        ], 401);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        /*$data = [
            "message" => "Sesión cerrada",
            "redirect_url" => route("inicio"),
            "success" => true,
        ];*/

        return redirect()->route("inicio");
    }
}
