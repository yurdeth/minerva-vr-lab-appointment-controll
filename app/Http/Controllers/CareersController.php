<?php

namespace App\Http\Controllers;

use App\Models\Careers;
use App\Http\Requests\StoreCareersRequest;
use App\Http\Requests\UpdateCareersRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CareersController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return DB::table('careers')->get();
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
    public function store(StoreCareersRequest $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request) {
        // Obtener las carreras a partir del department_id
        return DB::table('careers')->where('department_id', $request->id)->get();
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
    public function update(UpdateCareersRequest $request, Careers $careers) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Careers $careers) {
        //
    }
}
