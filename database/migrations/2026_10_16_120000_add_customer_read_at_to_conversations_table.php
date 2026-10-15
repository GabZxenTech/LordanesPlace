<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * When the customer/guest last read their conversation, so admin replies
     * after that count as unread (the red dot on the chat bubble). Existing
     * conversations start as read, so only replies from now on show the dot.
     */
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
