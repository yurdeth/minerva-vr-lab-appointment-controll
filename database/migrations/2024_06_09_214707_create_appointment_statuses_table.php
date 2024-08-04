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
        Schema::create('appointment_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_status');
            $table->timestamps();
        });

        DB::table('appointment_statuses')->insert([
            ['appointment_status' => 'Activa'],
            ['appointment_status' => 'Cancelada'],
            ['appointment_status' => 'Rechazada'],
            ['appointment_status' => 'Pendiente'],
            ['appointment_status' => 'Finalizada'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('appointment_statuses');
    }
};
