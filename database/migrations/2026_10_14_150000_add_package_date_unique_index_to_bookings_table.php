<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Only one active booking per non-room package per date.
    public function up(): void
    {
        if (in_array(Schema::getConnection()->getDriverName(), ['pgsql', 'sqlite'], true)) {
            DB::statement(
                "CREATE UNIQUE INDEX bookings_package_date_active_unique
                 ON bookings (package, event_date)
                 WHERE room_number IS NULL AND status NOT IN ('cancelled', 'rejected')"
            );
        }
    }

    public function down(): void
    {
        if (in_array(Schema::getConnection()->getDriverName(), ['pgsql', 'sqlite'], true)) {
            DB::statement('DROP INDEX IF EXISTS bookings_package_date_active_unique');
        }
    }
};
