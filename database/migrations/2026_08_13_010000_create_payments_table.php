<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Payments recorded by the admin. The total paid is always calculated from this table.
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        // Create payment rows for bookings already marked as paid before this table existed.
        $alreadyPaid = DB::table('bookings')
            ->where('payment_status', '!=', 'unpaid')
            ->whereNotNull('down_payment_amount')
            ->get(['id', 'payment_status', 'total_amount', 'down_payment_amount', 'down_payment_paid_at', 'created_at']);

        foreach ($alreadyPaid as $booking) {
            $amount = $booking->payment_status === 'fully_paid'
                ? $booking->total_amount
                : $booking->down_payment_amount;

            if ($amount <= 0) {
                continue;
            }

            DB::table('payments')->insert([
                'booking_id' => $booking->id,
                'amount' => $amount,
                'confirmed_by' => null,
                'note' => 'Backfilled from pre-ledger payment_status at migration time.',
                'created_at' => $booking->down_payment_paid_at ?? $booking->created_at,
                'updated_at' => $booking->down_payment_paid_at ?? $booking->created_at,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
