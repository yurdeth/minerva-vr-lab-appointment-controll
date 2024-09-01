<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\EmailUniqueIgnoreCase;
use App\Rules\OnlyUesMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $users = DB::table('users')
            ->where('users.id', '!=', 1)
            ->join('careers', 'users.career_id', '=', 'careers.id')
            ->join('departments', 'careers.department_id', '=', 'departments.id')
            ->select('users.id', 'users.name', 'users.email', 'careers.career_name', 'departments.department_name')
            ->get();

//        return view('usuarios', compact('users'));
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        // AuthController::register($request);
    }

    /**
     * Display the specified resource.
     */
    public function show($id) {
        if (Auth::user()->id != $id && Auth::user()->roles_id != 1) {
            return redirect()->route('HomeVR');
        }

        $user = DB::table('users')
            ->where('users.id', $id)
            ->join('careers', 'users.career_id', '=', 'careers.id')
            ->join('departments', 'careers.department_id', '=', 'departments.id')
            ->select('users.id', 'users.name', 'users.email', 'users.career_id', 'careers.career_name', 'careers.department_id', 'departments.department_name')
            ->get();

        if ($user) {
//            var_dump($user[0]->id);
//            var_dump(Auth::user()->id);
//            return view('edit_user', compact('user'));
            return response()->json($user);
        } else {
            return redirect()->route('usuarios');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        if (Auth::user()->id != $id && Auth::user()->roles_id != 1) {
            return redirect()->route('HomeVR');
        }

        // Actualizar los datos del usuario
        $user = User::find($id);

        $validate = Validator::make($request->all(), [
            "name" => "required|string",
            "email" => [
                "required",
                "string",
                new OnlyUesMail(),
            ],
            "password" => "confirmed|min:8",
            "department" => "required",
            "career" => "required",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "message" => "Error en los datos",
                "error" => $validate->errors(),
                "success" => false,
            ]);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->department_id = $request->department;
        $user->career_id = $request->career;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if (Auth::user()->roles_id == 1) {
            return $this->index();
        } else {
            $authController = new AuthController();
            return $authController->logout($request);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        if (Auth::user()->id != $id && Auth::user()->roles_id != 1) {
            return redirect()->route('HomeVR');
        }

        if ($id != 1) {
            $user = User::find($id);
            if ($user) {
                $user->delete();
            }
        }

        if (Auth::user()->roles_id == 1) {
            return redirect()->route('usuarios');
        } else {
            return redirect()->route('inicio');
        }
    }

}
