<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;

class ContactFormController extends Controller {
    public function sendEmail(): string {
        $details = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Este es un mensaje de prueba.'
        ];

        Mail::to('US21003@ues.edu.sv')->send(new ContactFormMail($details));

        return "Correo enviado correctamente";
    }
}
