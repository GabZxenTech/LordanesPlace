<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Client Guidelines | LorDane's Place</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-off-white text-warm-black font-body">

@include('partials._navbar')

<section class="py-[60px] md:py-[120px] px-[5%] lg:px-[8%]">
  <div class="max-w-[1000px] mx-auto bg-white border border-gold-deep/20 rounded-2xl p-10 md:p-24 shadow-2xl relative overflow-hidden">
    
    {{-- Decorative Accent --}}
    <div class="absolute top-0 left-0 w-full h-2 bg-gold-deep/30"></div>

    <div class="text-center mb-20">
        <h1 class="font-heading text-[42px] md:text-[64px] font-bold text-warm-black leading-tight tracking-[4px] uppercase mb-4">LorDane's Place</h1>
        <div class="flex items-center justify-center gap-4 mb-4">
            <div class="h-[1px] w-12 bg-gold-deep/40"></div>
            <h2 class="text-gold-deep font-bold tracking-[6px] text-[15px] md:text-[18px] uppercase">Client Guidelines for Booking</h2>
            <div class="h-[1px] w-12 bg-gold-deep/40"></div>
        </div>
    </div>

    <div class="border-t border-gold-deep/10 pt-16 mb-16">
        <h3 class="font-heading text-[32px] md:text-[40px] font-bold text-warm-black mb-16 italic border-b-4 border-gold-deep/10 pb-4 inline-block">Client Reservation Policies:</h3>
        
        <div class="space-y-16 text-warm-black/90 leading-[1.8] text-[18px] md:text-[20px]">
            
            {{-- 1. Reservation Fee --}}
            <div class="group">
                <div class="flex gap-6 items-start">
                    <span class="text-gold-deep font-heading text-[28px] font-bold opacity-40 group-hover:opacity-100 transition-opacity">01.</span>
                    <div>
                        <h4 class="font-heading text-[24px] md:text-[28px] font-bold text-warm-black mb-6 tracking-wide underline underline-offset-8 decoration-gold-deep/20">Reservation Fee</h4>
                        <ul class="list-none pl-0 space-y-4">
                            <li class="flex gap-4 items-start">
                                <span class="text-gold-deep mt-1">✦</span>
                                <span>A <strong>non-refundable reservation fee of ₱ 5,000</strong> is required to secure your booking.</span>
                            </li>
                            <li class="flex gap-4 items-start">
                                <span class="text-gold-deep mt-1">✦</span>
                                <span>The reservation fee will be deducted from the total bill.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- 2. Payment Terms --}}
            <div class="group">
                <div class="flex gap-6 items-start">
                    <span class="text-gold-deep font-heading text-[28px] font-bold opacity-40 group-hover:opacity-100 transition-opacity">02.</span>
                    <div>
                        <h4 class="font-heading text-[24px] md:text-[28px] font-bold text-warm-black mb-6 tracking-wide underline underline-offset-8 decoration-gold-deep/20">Payment Terms</h4>
                        <ul class="list-none pl-0 space-y-4">
                            <li class="flex gap-4 items-start">
                                <span class="text-gold-deep mt-1">✦</span>
                                <span>Full payment must be settled on or before the event date.</span>
                            </li>
                            <li class="flex gap-4 items-start">
                                <span class="text-gold-deep mt-1">✦</span>
                                <span>Accepted payment methods: <strong>Cash, GCash, and Bank Transfer</strong>.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- 3. Confirmation of Reservation --}}
            <div class="group">
                <div class="flex gap-6 items-start">
                    <span class="text-gold-deep font-heading text-[28px] font-bold opacity-40 group-hover:opacity-100 transition-opacity">03.</span>
                    <div>
                        <h4 class="font-heading text-[24px] md:text-[28px] font-bold text-warm-black mb-6 tracking-wide underline underline-offset-8 decoration-gold-deep/20">Confirmation of Reservation</h4>
                        <ul class="list-none pl-0 space-y-4">
                            <li class="flex gap-4 items-start">
                                <span class="text-gold-deep mt-1">✦</span>
                                <span>Reservations are considered confirmed only upon receipt of the reservation fee.</span>
                            </li>
                            <li class="flex gap-4 items-start">
                                <span class="text-gold-deep mt-1">✦</span>
                                <span>Failure to pay within <strong>14 days</strong> after filling out the form may result in automatic cancellation.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- 4. Cancellation Policy --}}
            <div class="group">
                <div class="flex gap-6 items-start">
                    <span class="text-gold-deep font-heading text-[28px] font-bold opacity-40 group-hover:opacity-100 transition-opacity">04.</span>
                    <div>
                        <h4 class="font-heading text-[24px] md:text-[28px] font-bold text-warm-black mb-6 tracking-wide underline underline-offset-8 decoration-gold-deep/20">Cancellation Policy</h4>
                        <ul class="list-none pl-0 space-y-4">
                            <li class="flex gap-4 items-start">
                                <span class="text-gold-deep mt-1">✦</span>
                                <span>Reservation fees are <strong>strictly non-refundable</strong>.</span>
                            </li>
                            <li class="flex gap-4 items-start">
                                <span class="text-gold-deep mt-1">✦</span>
                                <span>Cancellations made <strong>30-60 days</strong> before the event may be rebooked once (subject to availability).</span>
                            </li>
                            <li class="flex gap-4 items-start">
                                <span class="text-gold-deep mt-1">✦</span>
                                <span>No-shows on the reserved date will result in forfeiture of the reservation fee.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- 5. Rebooking / Rescheduling --}}
            <div class="group">
                <div class="flex gap-6 items-start">
                    <span class="text-gold-deep font-heading text-[28px] font-bold opacity-40 group-hover:opacity-100 transition-opacity">05.</span>
                    <div>
                        <h4 class="font-heading text-[24px] md:text-[28px] font-bold text-warm-black mb-6 tracking-wide underline underline-offset-8 decoration-gold-deep/20">Rebooking / Rescheduling</h4>
                        <ul class="list-none pl-0 space-y-4">
                            <li class="flex gap-4 items-start">
                                <span class="text-gold-deep mt-1">✦</span>
                                <span>Requests for rebooking must be made at least <strong>30 days</strong> prior to the reserved date.</span>
                            </li>
                            <li class="flex gap-4 items-start">
                                <span class="text-gold-deep mt-1">✦</span>
                                <span>Approval of rebooking depends on availability of schedule.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- 6. Changes in Reservation --}}
            <div class="group">
                <div class="flex gap-6 items-start">
                    <span class="text-gold-deep font-heading text-[28px] font-bold opacity-40 group-hover:opacity-100 transition-opacity">06.</span>
                    <div>
                        <h4 class="font-heading text-[24px] md:text-[28px] font-bold text-warm-black mb-6 tracking-wide underline underline-offset-8 decoration-gold-deep/20">Changes in Reservation</h4>
                        <ul class="list-none pl-0 space-y-4">
                            <li class="flex gap-4 items-start">
                                <span class="text-gold-deep mt-1">✦</span>
                                <span>Any changes in the number of guests, date, or other details must be communicated at least <strong>14 days</strong> before the event.</span>
                            </li>
                            <li class="flex gap-4 items-start">
                                <span class="text-gold-deep mt-1">✦</span>
                                <span>Additional charges may apply.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- 7. Client Responsibility --}}
            <div class="group">
                <div class="flex gap-6 items-start">
                    <span class="text-gold-deep font-heading text-[28px] font-bold opacity-40 group-hover:opacity-100 transition-opacity">07.</span>
                    <div>
                        <h4 class="font-heading text-[24px] md:text-[28px] font-bold text-warm-black mb-6 tracking-wide underline underline-offset-8 decoration-gold-deep/20">Client Responsibility</h4>
                        <ul class="list-none pl-0 space-y-4">
                            <li class="flex gap-4 items-start">
                                <span class="text-gold-deep mt-1">✦</span>
                                <span>The client is responsible for providing accurate information on the reservation form.</span>
                            </li>
                            <li class="flex gap-4 items-start">
                                <span class="text-gold-deep mt-1">✦</span>
                                <span>Damages incurred during the event/service shall be charged to the client.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="text-center mt-20 pt-16 border-t border-gold-deep/10">
        <p style="font-family: 'Cormorant Garamond', serif; font-size: 32px; font-weight: 700; color: #a88a4c; font-style: italic; margin-bottom: 3rem; letter-spacing: 1px;">Thank you and God Bless you!</p>
        <a href="{{ route('home') }}" 
           style="background: #1a1a1a; color: white; padding: 1.25rem 3.5rem; border-radius: 2px; text-decoration: none; font-weight: 700; display: inline-block; text-transform: uppercase; letter-spacing: 3px; font-size: 14px; transition: 0.3s; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
           I Have Read and Understand
        </a>
    </div>
  </div>
</section>

@include('partials._footer')

</body>
</html>
