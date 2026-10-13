<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Links the bookings created by one submission (a date range of
     * Staycation/Swimming days, or several packages added with "+ Add to
     * list") so they can be shown and handled as one reservation. Each day /
     * package is still its own booking. NULL for single bookings and for all
     * existing rows.
     */
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
