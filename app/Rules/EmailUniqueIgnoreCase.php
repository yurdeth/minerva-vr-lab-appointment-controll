<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class EmailUniqueIgnoreCase implements ValidationRule {
    protected $table;
    protected $column;

    public function __construct($table, $column = 'email') {
        $this->table = $table;
        $this->column = $column;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void {
        $email = strtolower($value);

        if (DB::table($this->table)->whereRaw("LOWER({$this->column}) = ?", [$email])->exists()) {
            $fail("The email has already been taken.");
        }
    }
}
