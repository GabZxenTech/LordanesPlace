<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Lets the admin hide visits without deleting them.
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
