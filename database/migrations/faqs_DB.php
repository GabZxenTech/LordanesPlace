<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Questions and answers for the FAQ page and the chat quick replies (edited by the admin).
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('question');
            $table->text('answer');
            $table->string('chat_label', 30)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        $now = now();
        $faqs = [
            ['Location & Hours', 'Where is LorDane\'s Place located?', "We are at Pulong Buhangin, Santa Maria, Bulacan, Philippines, near McDonald's Pulong Buhangin. You can see the map on our Contact Us page.", 'Location'],
            ['Location & Hours', 'What are your opening hours?', "We are open daily from 8:00 AM to 5:00 PM, 7 days a week. Swimming packages have their own time slots (see the next question).", null],
            ['Packages & Pricing', 'How much are your packages?', "From memorable events and relaxing swimming getaways to comfortable room stays, we have the perfect package for your next special moment!\n\nEnjoy our packages starting at just **₱4,500**!\n\nBook now and explore our packages on the Discover page.", 'Pricing'],
            ['Packages & Pricing', 'What are the time slots of the swimming packages?', "Day Swim: 8:00 AM – 4:00 PM\nNight Swim: 7:00 PM – 7:00 AM (next day)\n22 Hours Overnight: 2:00 PM – 12:00 NN (next day)\n\nSaturday and Sunday have a higher weekend rate.", null],
            ['Packages & Pricing', 'How many guests can you accommodate?', "The venue hall holds up to 200–220 guests. Swimming packages are good for up to 30 guests. Rooms: Standard Room up to 4, Family Room up to 7, Dormitory up to 15.", null],
            ['Packages & Pricing', 'What amenities are included?', "Depending on the package: private swimming pool, air-conditioned rooms, gazebos, griller and sink, kitchen access, videoke, and the venue hall with 2 preparation rooms. See each package on the Discover page for its full list.", 'Amenities'],
            ['Booking & Payment', 'How do I book?', "1. Create an account and verify your email.\n2. Go to Book Now, pick your date on the calendar, then choose the occasion and package.\n3. Submit your booking. Our admin will review it and contact you.\n4. Schedule your venue visit when asked.", 'Booking'],
            ['Booking & Payment', 'How much is the down payment?', "A **25% down payment** is required to confirm your reservation. The remaining balance is due on the event day. Our admin will send you the payment instructions, and your receipt becomes available once your payment is recorded.", 'Payment'],
            ['Booking & Payment', 'Can I book more than one day or package?', "Yes. Swimming and Staycation bookings can cover a date range (pick the first and last day on the calendar). To book several packages for the same date, use \"+ Add to list\" before confirming.", null],
            ['Booking & Payment', 'Why do I need a venue visit?', "A venue visit is required before your event so we can go over your plans with you. Schedule it before your event date. If a visit is missed and not rescheduled, the booking is automatically cancelled one day after the visit time.", null],
            ['Cancellation & Reschedule', 'Can I cancel my booking?', "You can cancel from your Profile & Bookings page while no payment has been recorded. Reservation fees are non-refundable. Unpaid bookings and missed visit schedules are automatically cancelled, and no-shows on the event date forfeit the reservation fee.", null],
            ['Cancellation & Reschedule', 'Can I reschedule my booking?', "Yes. Approved bookings can request a new date from your Profile & Bookings page, and our admin will review it. The first reschedule is free; each one after that costs ₱5,000.", null],
            ['Account', 'I forgot my password. What should I do?', "Click \"Forgot password?\" on the login page and enter your email. We will send you a code to set a new password.", null],
            ['Account', 'How do I update my profile or delete my account?', "Open Account Settings from the menu under your name. You can change your name, email, password and profile picture there. An account can be deleted once it has no pending, approved or ongoing bookings.", null],
        ];

        foreach ($faqs as $i => [$category, $question, $answer, $label]) {
            DB::table('faqs')->insert([
                'category' => $category, 'question' => $question, 'answer' => $answer, 'chat_label' => $label,
                'sort_order' => ($i + 1) * 10, 'is_published' => true, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
