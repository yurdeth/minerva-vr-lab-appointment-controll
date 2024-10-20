<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactFormController extends Controller {
    public function sendEmail(Request $request): JsonResponse{
        $details = [
            'subject' => $request->subject,
            'name' => 'Minerva RV Lab',
            'email' => $request->email,
            'message' => $request->message . $request->password
        ];

        Mail::to($request->email)->send(new ContactFormMail($details));

        return response()->json([
            'message' => 'Correo enviado correctamente a: ' . $request->email,
            'success' => true
        ]);
    }
}
