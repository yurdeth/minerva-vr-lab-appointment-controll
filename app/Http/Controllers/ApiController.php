<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;

class ApiController extends Controller {

    private $encryptionKey;
    private $apiKey;

    public function __construct() {
        $this->encryptionKey = env('API_ENCRYPTION_KEY');
        $this->apiKey = env('API_KEY');
    }

    public function encryptData($data) {
        $key = env('API_ENCRYPTION_KEY');
        $iv = random_bytes(16);

        // Asegurarse de que la clave tiene 32 bytes
        $key = substr(hash('sha256', $key, true), 0, 32);

        // Cifrar los datos
        $encryptedData = openssl_encrypt($data, env('ENCRYPTION_METHOD'), $key, OPENSSL_RAW_DATA, $iv);

        // Codificar el IV y los datos cifrados en base64 para fácil transferencia
        return base64_encode($iv . $encryptedData);
    }

    public function getKey(): JsonResponse {
        $data = env('PASSPHRASE');
        return response()->json(['xKey' => $this->encryptData($data)]);
    }
}
