<?php

namespace Database\Seeders;

use App\Models\NotificationType;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class NotificationTypeSeeder extends Seeder {
    /**
     * Run the database seeds.
     * @throws Exception
     */
    public function run(): void {
        try {
            NotificationType::create(['type' => 'Password recovering']);
            NotificationType::create(['type' => 'Default password']);
            NotificationType::create(['type' => 'Other']);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}
