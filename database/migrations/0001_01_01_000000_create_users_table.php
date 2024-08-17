<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('department_name');
            $table->timestamps();
        });

        // Insertar datos en la tabla departments
        DB::table('departments')->insert([
            ['department_name' => 'Ingeniería y Arquitectura'],
            ['department_name' => 'Medicina'],
        ]);

        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('career_name');
            $table->foreignId('department_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
        });

        // Insertar datos en la tabla careers
        DB::table('careers')->insert([
            ['career_name' => 'Arquitectura', 'department_id' => 1],
            ['career_name' => 'Ingeniería Civil', 'department_id' => 1],
            ['career_name' => 'Ingeniería de Sistemas Informáticos', 'department_id' => 1],
            ['career_name' => 'Ingeniería Eléctrica', 'department_id' => 1],
            ['career_name' => 'Ingeniería Industrial', 'department_id' => 1],
            ['career_name' => 'Ingeniería Mecánica', 'department_id' => 1],

            ['career_name' => 'Laboratorio Clínico', 'department_id' => 2],
            ['career_name' => 'Doctorado en Medicina', 'department_id' => 2],
            ['career_name' => 'Anestesiología e Inhaloterapia', 'department_id' => 2],
            ['career_name' => 'Fisioterapia y Terapia Ocupacional', 'department_id' => 2],
        ]);

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_name');
            $table->timestamps();
        });

        // Insertar datos en la tabla roles
        DB::table('roles')->insert([
            ['role_name' => 'admin'],
            ['role_name' => 'user'],
        ]);

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->foreignId('career_id')
                ->constrained('careers')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
//            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('roles_id')
                ->constrained('roles')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
//            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
