<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Postgres implements Laravel's enum() as a CHECK constraint (same pattern
     * as 2026_08_06_140100_add_new_statuses_to_bookings_status_check.php).
     * Adds 'missed' so a required venue visit that was never attended can be
     * recorded distinctly from 'pending'/'confirmed'/'rescheduled'/'completed'.
     */
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
