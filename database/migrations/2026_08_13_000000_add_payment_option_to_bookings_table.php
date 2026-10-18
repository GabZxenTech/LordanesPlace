<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Payment option chosen by the customer (checked in the app).
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
