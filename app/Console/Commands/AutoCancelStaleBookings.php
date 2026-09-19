<?php

namespace App\Console\Commands;

use App\Services\BookingAutoCancelService;
use Illuminate\Console\Command;

class AutoCancelStaleBookings extends Command
{
    protected $signature = 'bookings:auto-cancel';

    protected $description = 'Cancel same-day-or-overdue unpaid bookings and bookings whose required venue visit was missed.';

    public function handle(): int
    {
        $unpaid = BookingAutoCancelService::cancelUnpaidPastDue();
        $missed = BookingAutoCancelService::cancelMissedVisits();

        $this->info("Auto-cancelled {$unpaid} unpaid booking(s) and {$missed} booking(s) for a missed venue visit.");

        return self::SUCCESS;
    }
}
