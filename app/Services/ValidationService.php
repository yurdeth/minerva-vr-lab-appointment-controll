<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class ValidationService {

    protected $request;
    protected $validationType;

    public function __construct($request, $validationType) {
        $this->request = $request;
        $this->validationType = $validationType;
    }

    public function ValidateRequest(): ?JsonResponse {
        if ($this->validationType == "register") {
            return $this->validateRegister();
        }

        return null;
    }

    private function errorResponse($message): JsonResponse {
        return response()->json([
            'message' => $message,
            'success' => false
        ]);
    }

    private function validateRegister(): ?JsonResponse {
        if (!$this->request->name && !$this->request->email && !$this->request->career && !$this->request->password && !$this->request->password_confirmation) {
            return $this->errorResponse("Todos los campos son requeridos");
        }

        if (!$this->request->name) {
            return $this->errorResponse("El nombre es requerido");
        }

        if (is_numeric($this->request->name)) {
            return $this->errorResponse("El nombre no puede ser un valor numérico");
        }

        if (!preg_match("/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]*$/", $this->request->name)) {
            return $this->errorResponse("No se permite el uso de números ni caracteres especiales en el nombre");
        }

        if (strlen($this->request->name) < 3) {
            return $this->errorResponse("El nombre debe tener al menos 3 caracteres");
        }

        if (!$this->request->email) {
            return $this->errorResponse("El correo es requerido");
        }

        if (is_numeric($this->request->email)) {
            return $this->errorResponse("El correo no puede ser un valor numérico");
        }

        if(strlen(explode("@", $this->request->email)[0]) < 5) {
            return $this->errorResponse("El correo no coincide con los formatos permitidos");
        }

        if (!preg_match("/^[a-zA-ZñÑáéíóúÁÉÍÓÚ@.1234567890 ]*$/", $this->request->email)) {
            return $this->errorResponse("No se permite el uso de caracteres especiales en el correo");
        }

        if (substr_count($this->request->email, '@') != 1) {
            return $this->errorResponse("El correo debe contener exactamente una arroba (@)");
        }

        if (substr_count($this->request->email, '.') > 3) {
            return $this->errorResponse("El correo debe contener un máximo de tres puntos (.)");
        }

        if (!$this->request->career) {
            return $this->errorResponse("Seleccione su carrera");
        }

        if (!$this->request->password) {
            return $this->errorResponse("La contraseña es requerido");
        }

        if (!$this->request->password_confirmation) {
            return $this->errorResponse("La confirmación de la contraseña es requerida");
        }

        if ($this->request->password != $this->request->password_confirmation) {
            return $this->errorResponse("Las contraseñas no coinciden");
        }

        return null;
    }
}
