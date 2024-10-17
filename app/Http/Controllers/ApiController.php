<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller {

    private $encryptionKey;

    public function __construct() {
        $this->encryptionKey = env('API_ENCRYPTION_KEY');
    }

    public function encryptData($data) {
        $key = $this->encryptionKey;
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

        $xKey = $this->encryptData($data);
        Log::info('Encrypted xKey: ' . $xKey);
        return response()->json([
            'xKey' => $xKey
        ]);
    }
}
