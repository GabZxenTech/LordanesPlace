<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * When a customer deletes a finished booking from "My Reservations" it is
     * only hidden from them (this timestamp is set). The booking, its
     * payments and visits stay for the admin, reports and history.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->timestamp('hidden_by_customer_at')->nullable()->after('cancellation_reason');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('hidden_by_customer_at');
        });
    }
};
