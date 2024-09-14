<?php

namespace App\Enum;

enum ValidationTypeEnum: string {
    case REGISTER_USER = "register";
    case CAREER = "career";
    case DEPARTMENT = "department";
}
