<?php

namespace App\Http\Controllers;

use App\Models\Resources;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResourcesController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $resources = Resources::with(['room', 'status', 'resourceType'])->get();

        if ($resources->isEmpty()) {
            return response()->json([
                'message' => 'No resources found',
                'total' => 0,
            ]);
        }

        $totalResources = Resources::count();

        $data = [
            'message' => 'List of resources',
            'resources' => $resources,
            'total' => $totalResources
        ];

        return response()->json($data);
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
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
                "fixed_asset_code" => "required|unique:resources,fixed_asset_code", // Número de activo fijo
                "resource_type_id" => "required|exists:resource_types,id",
                "status_id" => "required|exists:statuses,id",
                "room_id" => "required|exists:room,id",
            ]
        );

        if ($validator->fails()) {
            $data = [
                'message' => 'Error validating data',
                'errors' => $validator->errors(),
                'status' => 400
            ];

            return response()->json($data);
        }

        $resource = Resources::create([
            "fixed_asset_code" => $request->fixed_asset_code,
            "resource_type_id" => $request->resource_type_id,
            "status_id" => $request->status_id,
            "room_id" => $request->room_id,
        ]);

        if (!$resource) {
            return response()->json([
                'message' => 'Error creating the resource',
                'status' => 500
            ]);
        }

        $resource->save();

        return response()->json([
            'message' => 'Resource created successfully',
            'resource' => $resource,
            'status' => 201
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id) {
        $resource = Resources::with(['room', 'status', 'resourceType'])->find($id);

        if (!$resource) {
            return response()->json([
                'message' => 'Resource not found',
                'status' => 404
            ]);
        }

        return response()->json([
            'message' => 'Resource found',
            'resource' => $resource,
            'status' => 200
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resources $resources) {
        $validator = Validator::make($request->all(), [
                "fixed_asset_code" => "required|unique:resources,fixed_asset_code", // Número de activo fijo
                "resource_type_id" => "required|exists:resource_types,id",
                "status_id" => "required|exists:statuses,id",
                "room_id" => "required|exists:room,id",
            ]
        );

        if ($validator->fails()) {
            $data = [
                'message' => 'Error validating data',
                'errors' => $validator->errors(),
                'status' => 400
            ];

            return response()->json($data);
        }

        $resources->fixed_asset_code = $request->fixed_asset_code;
        $resources->resource_type_id = $request->resource_type_id;
        $resources->status_id = $request->status_id;
        $resources->room_id = $request->room_id;

        $resources->save();

        return response()->json([
            'message' => 'Resource updated successfully',
            'resource' => $resources,
            'status' => 200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id) {
        $resource = Resources::find($id);

        if (!$resource) {
            return response()->json([
                'message' => 'Resource not found',
                'status' => 404
            ]);
        }

        $resource->delete();

        return response()->json([
            'message' => 'Resource deleted successfully',
            'success' => true,
            'status' => 200
        ]);
    }
}
