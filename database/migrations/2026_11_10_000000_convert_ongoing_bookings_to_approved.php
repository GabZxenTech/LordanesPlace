<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // The ongoing status is gone: those bookings are approved now.
    public function up(): void
    {
        DB::table('bookings')->where('status', 'ongoing')->update(['status' => 'approved']);
    }

    public function down(): void
    {
        // Cannot tell which approved bookings used to be ongoing.
    }
};
