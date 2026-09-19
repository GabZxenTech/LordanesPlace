<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('room_number')->nullable()->after('package');
        });

        // Postgres partial unique index: only one non-cancelled/rejected
        // booking may hold a given room on a given date at a time. A backstop
        // against a true concurrent double-submit, on top of the application
        // check in BookingController::store().
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement(
                "CREATE UNIQUE INDEX bookings_room_date_active_unique
                 ON bookings (package, room_number, event_date)
                 WHERE room_number IS NOT NULL AND status NOT IN ('cancelled', 'rejected')"
            );
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS bookings_room_date_active_unique');
        }

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('room_number');
        });
    }
};
