<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * When the admin last read each conversation, so customer/guest messages
     * after that count as unread (the red badge on "Chat" in the admin
     * sidebar). Existing conversations start as read, so the badge only
     * counts messages that arrive from now on.
     */
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->timestamp('admin_read_at')->nullable()->after('last_message_at');
        });

        DB::table('conversations')->update(['admin_read_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn('admin_read_at');
        });
    }
};
