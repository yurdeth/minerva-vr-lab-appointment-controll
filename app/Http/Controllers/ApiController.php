<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller {
    public function getKey(): JsonResponse {
        return response()->json([
            'xKey' => $this->encrypt()
        ]);
    }

    protected function encrypt(): string {
        return Crypt::encrypt(env('API_KEY'));
    }
}
