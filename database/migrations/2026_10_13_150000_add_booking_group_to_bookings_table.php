<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Links bookings made together (date range or "+ Add to list").
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('booking_group', 36)->nullable()->index()->after('booking_number');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['booking_group']);
            $table->dropColumn('booking_group');
        });
    }
};
