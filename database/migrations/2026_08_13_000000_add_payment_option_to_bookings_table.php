<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A plain string (validated at the app layer via Booking::PAYMENT_OPTIONS)
     * rather than a DB-level enum/CHECK constraint — adding a third payment
     * option later is then a one-line change here instead of another
     * constraint-rewrite migration.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_option', 20)->default('downpayment')->after('down_payment_amount');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('payment_option');
        });
    }
};
