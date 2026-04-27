<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('reschedule_count')->default(0)->after('status');
            $table->string('reschedule_status')->nullable()->after('reschedule_count');
            $table->date('requested_event_date')->nullable()->after('reschedule_status');
            $table->date('requested_visit_date')->nullable()->after('requested_event_date');
            $table->text('reschedule_reason')->nullable()->after('requested_visit_date');
            $table->integer('reschedule_fee')->nullable()->after('reschedule_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'reschedule_count',
                'reschedule_status',
                'requested_event_date',
                'requested_visit_date',
                'reschedule_reason',
                'reschedule_fee',
            ]);
        });
    }
};
