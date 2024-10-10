<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller {

    private $encryptionKey;
    private $encryptionIV;
    private $tag;

    /**
     * @param $encryptionKey
     * @param $encryptionIV
     */
    public function __construct() {
        $this->encryptionKey = env('API_ENCRYPTION_KEY');
        $this->encryptionIV = env('API_ENCRYPTION_IV');
        $this->tag = env('API_TAG');
    }

    protected function encrypt($apiKey): string {
        return base64_encode(openssl_encrypt(
            $apiKey,
            env('ENCRYPTION_METHOD'),
            $this->encryptionKey,
            0,
            $this->encryptionIV,
            $this->tag
        ));
    }

    public function getKey(): JsonResponse {

        $apiKey = env('API_KEY');
        $encrypted = $this->encrypt($apiKey);

        return response()->json([
            'xKey' => $encrypted
        ]);
    }
}
