<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Customer bookings: package, date, payment and reschedule details.
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->nullable()->unique();
            $table->string('booking_group', 36)->nullable()->index();
            $table->foreignId('user_id')->index()->constrained()->onDelete('cascade');
            $table->string('event_type');
            $table->string('package');
            $table->string('room_number')->nullable();
            $table->date('event_date')->index();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('guest_count');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'ongoing', 'completed'])->default('pending')->index();
            $table->text('cancellation_reason')->nullable();

            $table->decimal('total_amount', 10, 2)->nullable();
            $table->decimal('down_payment_amount', 10, 2)->nullable();
            $table->string('payment_option', 20)->default('downpayment');
            $table->enum('payment_status', ['unpaid', 'partially_paid', 'fully_paid'])->default('unpaid')->index();
            $table->timestamp('down_payment_paid_at')->nullable();

            $table->integer('reschedule_count')->default(0);
            $table->string('reschedule_status')->nullable()->index();
            $table->date('requested_event_date')->nullable();
            $table->date('requested_visit_date')->nullable();
            $table->text('reschedule_reason')->nullable();
            $table->integer('reschedule_fee')->nullable();

            $table->timestamp('hidden_by_customer_at')->nullable();
            $table->timestamps();
        });

        // One active booking per room per date, and per non-room package per date.
        DB::statement("CREATE UNIQUE INDEX bookings_room_date_active_unique ON bookings (package, room_number, event_date) WHERE room_number IS NOT NULL AND status NOT IN ('cancelled', 'rejected')");
        DB::statement("CREATE UNIQUE INDEX bookings_package_date_active_unique ON bookings (package, event_date) WHERE room_number IS NULL AND status NOT IN ('cancelled', 'rejected')");
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
