<?php

namespace App\Services;

use App\Enum\ValidationTypeEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ValidateUpdateService extends ValidationService {

    protected $request;
    protected $validationType;

    public function __construct($request, $validationType) {
        parent::__construct($request, $validationType);
        $this->request = $request;
        $this->validationType = $validationType;
    }

    public function ValidateRequest(): ?JsonResponse {
        /*if ($this->validationType == ValidationTypeEnum::REGISTER_USER) {
            return $this->validateRegister();
        }*/

        if ($this->validationType == ValidationTypeEnum::DEPARTMENT) {
            return $this->validateDepartmentName($this->request->department_name);
        }

        /*if ($this->validationType == ValidationTypeEnum::CAREER) {
            return $this->validateCareerName($this->request->career_name);
        }*/

        return null;
    }

    protected function errorResponse($message): JsonResponse {
        return response()->json([
            'message' => $message,
            'success' => false
        ]);
    }

    protected function validateDepartmentName($departmentName): ?JsonResponse {
        $response = parent::validateDepartmentName($departmentName);
        if ($response) {
            return $response;
        }

        return $this->departmentExists($departmentName);
    }

    private function departmentExists($departmentName): ?JsonResponse {
        $department = DB::table('departments')->where('department_name', $departmentName)->first();
        if ($department) {
            return $this->errorResponse("El departamento ya existe");
        }

        return null;
    }
}
