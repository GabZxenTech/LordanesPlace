<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lets an admin remove old visits from the Visit Schedules list without
     * deleting the row (booking history keeps it). Existing rows are untouched.
     */
    public function up(): void
    {
        Schema::table('visit_schedules', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('visit_schedules', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
