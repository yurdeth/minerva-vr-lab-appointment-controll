<?php

namespace App\Http\Controllers;

use App\Models\Careers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CareersController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $careers = new Careers();
        return $careers->getCareers();
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
        $validationResponse = $this->validateCareerName($request->career_name);
        if ($validationResponse) {
            return $validationResponse;
        }

        $validate = Validator::make($request->all(), [
            'career_name' => 'required|string|unique:careers,career_name',
            'department_id' => 'required|integer'
        ]);

        if($validate->fails()){
            return response()->json([
                'message' => 'Error en los datos',
                'error' => $validate->errors(),
                'success' => false,
            ]);
        }

        $career = Careers::create([
            'career_name' => $request->career_name,
            'department_id' => $request->department_id
        ]);

        if(!$career){
            return response()->json([
                'message' => 'Error al crear el registro',
                'success' => false,
            ]);
        }

        $career->save();

        return response()->json([
            'message' => 'Carrera agregada correctamente',
            'success' => true,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request) {
        // Obtener las carreras a partir del department_id
        return DB::table('careers')->where('department_id', $request->id)->get();
    }

    public function getCareerData(Request $request): Collection {
        // Obtener el nombre de la carrera y el nombre del departamento a partir del career_id
        return DB::table('careers')
            ->join('departments', 'careers.department_id', '=', 'departments.id')
            ->where('careers.id', $request->id)
            ->select('careers.id', 'careers.career_name', 'careers.department_id', 'departments.department_name')
            ->get();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Careers $careers) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request) {
        $validationResponse = $this->validateCareerName($request->career_name);
        if ($validationResponse) {
            return $validationResponse;
        }

        if(!$request->department_id){
            return response()->json([
                'message' => 'Error: no se ha proporcionado un departamento',
                'success' => false
            ]);
        }

        $career = Careers::find($request->id);

        if (!$career) {
            return response()->json([
                'message' => 'Carrera no encontrada',
                'success' => false
            ], 404);
        }

        $career->career_name = $request->career_name;
        $career->department_id = $request->department_id;
        $career->save();

        return response()->json([
            'message' => 'Carrera actualizada',
            'success' => true
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        $career = Careers::find($id);

        if (!$career) {
            return response()->json([
                'message' => 'Carrera no encontrada',
                'success' => false
            ], 404);
        }

        $career->delete();

        return response()->json([
            'message' => 'Carrera eliminada',
            'success' => true
        ], 201);
    }

    private function validateCareerName($careerName): ?JsonResponse {
        $maxLength = 50;

        if (is_numeric($careerName)) {
            return $this->errorResponse('Error: el nombre de la carrera no puede ser un valor numérico');
        }

        if (strlen($careerName) > $maxLength) {
            return $this->errorResponse('Error: el nombre de la carrera no puede exceder los ' . $maxLength . ' caracteres');
        }

        if (strlen($careerName) < 5) {
            return $this->errorResponse('Error: el nombre de la carrera debe tener al menos 5 caracteres');
        }

        if ($careerName == null) {
            return $this->errorResponse('Error: el nombre de la carrera no puede estar vacío');
        }

        if (!preg_match("/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]*$/", $careerName)) {
            return $this->errorResponse('Error: el nombre de la carrera no puede contener símbolos o caracteres especiales, excepto las tildes.');
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
