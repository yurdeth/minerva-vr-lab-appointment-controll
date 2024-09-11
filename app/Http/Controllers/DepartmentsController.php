<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use App\Http\Requests\StoreDepartmentsRequest;
use App\Http\Requests\UpdateDepartmentsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     * Show the form for creating a new resource.
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse {
        $validate = Validator::make($request->all(), [
            "department_name" => "required|string|unique:departments,department_name",
        ]);

        if($validate->fails()){
            return response()->json([
                'message' => 'Error en los datos',
                'error' => $validate->errors(),
                'success' => false,
            ]);
        }

        $department = Departments::create([
            'department_name' => $request->department_name,
        ]);

        if(!$department){
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
    public function show(Departments $departments) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departments $departments) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentsRequest $request, Departments $departments) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departments $departments) {
        //
    }
}
