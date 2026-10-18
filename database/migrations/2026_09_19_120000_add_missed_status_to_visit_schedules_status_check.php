<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Adds the "missed" visit status.
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE visit_schedules DROP CONSTRAINT IF EXISTS visit_schedules_status_check');
            DB::statement("ALTER TABLE visit_schedules ADD CONSTRAINT visit_schedules_status_check CHECK (status IN ('pending', 'confirmed', 'rescheduled', 'completed', 'missed'))");
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE visit_schedules DROP CONSTRAINT IF EXISTS visit_schedules_status_check');
            DB::statement("ALTER TABLE visit_schedules ADD CONSTRAINT visit_schedules_status_check CHECK (status IN ('pending', 'confirmed', 'rescheduled', 'completed'))");
        }
    }
};
