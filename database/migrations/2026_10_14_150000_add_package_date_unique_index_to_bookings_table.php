<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Partial unique index: only one active (not cancelled/rejected) booking
     * may hold a non-room package (Swimming / Event) on a given date — the
     * counterpart of bookings_room_date_active_unique for rooms. A backstop
     * against two customers submitting at the same moment, on top of the
     * check in BookingController::store(). Existing duplicates must be
     * resolved first or this fails (nothing is changed in that case).
     */
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
