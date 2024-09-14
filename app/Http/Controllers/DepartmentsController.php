<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DepartmentsController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return DB::table('departments')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse {
        $validationResponse = $this->validateDepartmentName($request->department_name);
        if ($validationResponse) {
            return $validationResponse;
        }

        $validate = Validator::make($request->all(), [
            "department_name" => "required|string|unique:departments,department_name",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Error en los datos',
                'error' => $validate->errors(),
                'success' => false,
            ]);
        }

        $department = Departments::create([
            'department_name' => $request->department_name,
        ]);

        if (!$department) {
            return response()->json([
                'message' => 'Error al crear el registro',
                'success' => false,
            ]);
        }

        $department->save();

        return response()->json([
            'message' => 'Departamento agregado correctamente',
            'success' => true,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): Collection {
        return DB::table('departments')
            ->where('id', $request->id)
            ->select('id', 'department_name')
            ->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request) {
        $validationResponse = $this->validateDepartmentName($request->department_name);
        if ($validationResponse) {
            return $validationResponse;
        }

        $department = Departments::find($request->id);

        if (!$department) {
            return response()->json([
                'message' => 'Departamento no encontrado',
                'success' => false
            ], 404);
        }

        $department->department_name = $request->department_name;
        $department->save();

        return response()->json([
            'message' => 'Departamento actualizado',
            'success' => true
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse {
        $department = Departments::find($id);

        if (!$department) {
            return response()->json([
                'message' => 'El departamento solicitado no ha podido encontrarse',
                'success' => false
            ], 404);
        }

        $department->delete();

        return response()->json([
            'message' => 'Departamento eliminado correctamente',
            'success' => true
        ], 201);
    }

    private function validateDepartmentName($departmentName): ?JsonResponse {
        if (is_numeric($departmentName)) {
            return $this->errorResponse('Error: el nombre del departamento no puede ser un valor numérico');
        }

        if (strlen($departmentName) > 50) {
            return $this->errorResponse('Error: el nombre del departamento no puede exceder los 50 caracteres');
        }

        if (strlen($departmentName) < 5) {
            return $this->errorResponse('Error: el nombre del departamento debe tener al menos 5 caracteres');
        }

        if ($departmentName == null) {
            return $this->errorResponse('Error: el nombre del departamento no puede estar vacío');
        }

        if (!preg_match("/^[a-zA-ZñÑ ]*$/", $departmentName)) {
            return $this->errorResponse('Error: el nombre del departamento no puede contener simbolos o caracteres especiales');
        }

        return null;
    }

    private function errorResponse($message): JsonResponse {
        return response()->json([
            'message' => $message,
            'success' => false
        ]);
    }
}
