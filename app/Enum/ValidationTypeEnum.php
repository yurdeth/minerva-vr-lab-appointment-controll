<?php

namespace App\Enum;

enum ValidationTypeEnum: string {
    case REGISTER_USER = "register";
    case CAREER = "career";
    case DEPARTMENT = "department";
    case UPDATE_USER_INFO = "update_user";
    case UPDATE_DEPARTMENT = "update_department";
    case UPDATE_CAREER = "update_career";
}
