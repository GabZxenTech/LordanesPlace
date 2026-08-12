<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Replaces the generic Laravel database-notifications table with an
     * app-specific schema (recipient_type/recipient_id/booking_id/title/type)
     * needed for the admin + customer notification center. Any existing rows
     * are converted rather than discarded.
     */
    public function up(): void
    {
        $legacy = Schema::hasTable('notifications') ? DB::table('notifications')->get() : collect();

        Schema::dropIfExists('notifications');

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_type'); // 'admin' | 'customer'
            $table->foreignId('recipient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->string('title');
            $table->text('message');
            $table->string('type');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['recipient_type', 'recipient_id', 'is_read']);
        });

        foreach ($legacy as $row) {
            $data = json_decode($row->data, true) ?? [];

            DB::table('notifications')->insert([
                'recipient_type' => 'customer',
                'recipient_id' => $row->notifiable_id,
                'booking_id' => $data['booking_id'] ?? null,
                'title' => 'Event Schedule Updated',
                'message' => $data['message'] ?? '',
                'type' => 'schedule_updated',
                'is_read' => ! is_null($row->read_at),
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
