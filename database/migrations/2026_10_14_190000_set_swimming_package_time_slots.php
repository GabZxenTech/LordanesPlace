<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Fixed time slots for the swimming packages (admins can still change
     * them on the Packages page). Only packages whose time is still unset are
     * filled. Their active bookings (pending / approved / ongoing) that have
     * no time yet get the same slot; a time an admin already assigned is
     * left as it is. An end time earlier than the start means the next day.
     */
    private const SLOTS = [
        'Day Swim'           => ['08:00:00', '16:00:00'],
        'Night Swim'         => ['19:00:00', '07:00:00'],
        '22 Hours Overnight' => ['14:00:00', '12:00:00'],
    ];

    public function up(): void
    {
        foreach (self::SLOTS as $package => [$start, $end]) {
            DB::table('packages')
                ->where('name', $package)
                ->whereNull('start_time')
                ->whereNull('end_time')
                ->update(['start_time' => $start, 'end_time' => $end, 'updated_at' => now()]);

            DB::table('bookings')
                ->where('package', $package)
                ->whereIn('status', ['pending', 'approved', 'ongoing'])
                ->whereNull('start_time')
                ->whereNull('end_time')
                ->update(['start_time' => $start, 'end_time' => $end, 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        // Data fill only — nothing to undo safely (admins may have edited the
        // times since).
    }
};
