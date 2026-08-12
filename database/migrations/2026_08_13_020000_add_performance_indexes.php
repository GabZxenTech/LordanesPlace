<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Indexes for columns actually filtered/sorted on by the app (confirmed
     * via grep, not guessed). Postgres does not auto-index foreign key
     * columns the way some other databases do, so payments.booking_id and
     * visit_schedules.booking_id were genuinely missing an index despite
     * being queried on every booking row.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->index('event_date');
            $table->index('status');
            $table->index('user_id');
            $table->index('reschedule_status');
            $table->index('payment_status');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index('booking_id');
        });

        Schema::table('visit_schedules', function (Blueprint $table) {
            $table->index('booking_id');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['event_date']);
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['reschedule_status']);
            $table->dropIndex(['payment_status']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['booking_id']);
        });

        Schema::table('visit_schedules', function (Blueprint $table) {
            $table->dropIndex(['booking_id']);
        });
    }
};
