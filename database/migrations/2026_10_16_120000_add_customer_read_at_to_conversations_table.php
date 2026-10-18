<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // When the customer last read their conversation (existing ones start as read).
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->timestamp('customer_read_at')->nullable()->after('admin_read_at');
        });

        DB::table('conversations')->update(['customer_read_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn('customer_read_at');
        });
    }
};
