<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use App\Rules\OnlyUesMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationsController extends Controller {
    public function index() {
        return view('notifications.notifications');
    }

    public function store(Request $request): JsonResponse {
        $rules = [
            'from' => ['required', 'email', new OnlyUesMail()],
            'type_id' => 'required'
        ];

        if ($request->type_id == '3') {
            $rules['description'] = 'required|max:100';
        }

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Error en los datos',
                'error' => $validate->errors(),
                'success' => false
            ], 400);
        }

        $notification = Notifications::create([
            'from' => $request->from,
            'description' => $request->description,
            'type_id' => $request->type_id,
            'reviewed' => false
        ]);

        if (!$notification) {
            return response()->json([
                'message' => 'Error al contactar con el administrador',
                'success' => false
            ], 500);
        }

        return response()->json([
            'message' => 'Mensaje enviado correctamente al administrador.',
            'success' => true
        ], 201);
    }

    public function show($id) {
        $notification = Notifications::find($id);
        return view('notifications.show', compact('notification'));
    }

    public function countNotifications() {
        $notifications = Notifications::where('reviewed', false)->count();
        return response()->json($notifications);
    }
}
