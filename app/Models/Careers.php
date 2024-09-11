<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Careers extends Model {
    use HasFactory;

    public function getCareers(): \Illuminate\Support\Collection {

        return DB::table('careers')
            ->join('departments', 'careers.department_id', '=', 'departments.id')
            ->select('careers.id', 'careers.career_name', 'departments.department_name')
            ->orderBy('departments.department_name')
            ->get();
    }

}
