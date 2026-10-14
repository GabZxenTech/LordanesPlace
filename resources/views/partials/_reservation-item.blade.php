{{-- One reservation's details + money/actions columns. Used for single
     tickets and inside a same-date group on the profile page. --}}
@php
  $isDead = $booking->isCancelledOrRejected();
  $paid = $booking->amountPaid();
  $total = (float) $booking->total_amount;
  $pct = $total > 0 ? min(100, (int) round($paid / $total * 100)) : 0;
  $visit = $booking->visitSchedules->first();
  $steps = ['pending' => 'Pending', 'approved' => 'Approved', 'ongoing' => 'Ongoing', 'completed' => 'Completed'];
  $stepIndex = array_search($booking->status, array_keys($steps), true);
  $isUpcomingDate = $booking->event_date->format('Y-m-d') >= date('Y-m-d');
  $occasionLine = in_array($booking->event_type, ['Swimming', 'Staycation'], true)
      ? $booking->event_type
      : 'Event · ' . $booking->event_type;
@endphp

<div class="rv-body">
  <div class="rv-title">
    <h3>{{ $booking->package }}{{ $booking->room_number ? ' ' . $booking->room_number : '' }}</h3>
    <span class="rv-occ">{{ $occasionLine }}</span>
  </div>

  <div class="rv-meta">
    <span class="rv-ref">#{{ $booking->booking_number }}</span>
    @if(!empty($showDate))
      <span><b>{{ $booking->event_date->format('D, M d') }}</b></span>
    @endif
    <span>{{ $booking->guest_count }} {{ $booking->guest_count == 1 ? 'guest' : 'guests' }}</span>
    <span>
      @if($booking->scheduleLabel())
        {{ $booking->scheduleLabel() }}
      @else
        <em>Schedule set during your venue visit</em>
      @endif
    </span>
  </div>

  <div class="rv-chips">
    <span class="rv-chip rv-s-{{ $booking->status }}">{{ $booking->status }}</span>
    @if(!$isDead)
      @if($booking->payment_status === 'fully_paid')
        <span class="rv-chip rv-p-paid">Fully paid</span>
      @elseif($booking->payment_status === 'partially_paid')
        <span class="rv-chip rv-p-partial">Partially paid</span>
      @else
        <span class="rv-chip rv-p-unpaid">Unpaid</span>
      @endif
    @endif
    @if($booking->reschedule_status === 'pending' && $booking->requested_event_date)
      <span class="rv-chip rv-p-partial">Reschedule requested → {{ $booking->requested_event_date->format('M d, Y') }}</span>
    @endif
  </div>

  @if($isDead)
    @if($booking->cancellation_reason)
      <p class="rv-reason"><strong>{{ $booking->status === 'rejected' ? 'Reason for rejection:' : 'Reason for cancellation:' }}</strong> {{ $booking->cancellation_reason }}</p>
    @endif
  @else
    @php
      // Offer to book a visit only while the booking is still ahead of us.
      $canScheduleVisit = !$visit && $isUpcomingDate && in_array($booking->status, ['pending', 'approved'], true);
    @endphp
    @if($visit || $canScheduleVisit)
      <div class="rv-visit">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        @if($visit)
          <span>Venue visit: {{ $visit->visit_date->format('M d, Y · h:i A') }}</span>
          <span class="rv-muted">· {{ $visit->status === 'confirmed' ? 'Confirmed' : ($visit->status === 'completed' ? 'Completed' : ($visit->status === 'missed' ? 'Missed' : 'Awaiting confirmation')) }}</span>
        @else
          <a href="{{ route('visit-schedule.create', ['booking' => $booking->id]) }}" class="rv-link">Schedule your venue visit →</a>
        @endif
      </div>
    @endif

    @if($stepIndex !== false)
      <div class="rv-steps" aria-label="Status: {{ $booking->status }}">
        <div class="rv-track">
          @foreach($steps as $key => $label)
            <div class="rv-step {{ $loop->index <= $stepIndex ? 'done' : '' }} {{ $loop->index === $stepIndex ? 'now' : '' }}">
              <span class="rv-dot"></span>@if(!$loop->last)<span class="rv-bar"></span>@endif
            </div>
          @endforeach
        </div>
        <div class="rv-step-labels">
          @foreach($steps as $key => $label)
            <span class="{{ $key === $booking->status ? 'cur' : '' }}">{{ $label }}</span>
          @endforeach
        </div>
      </div>
    @endif
  @endif

  @if($booking->notes)
    <p class="rv-note">“{{ $booking->notes }}”</p>
  @endif
</div>

<div class="rv-money">
  <div class="rv-total"><small>Total</small>₱{{ number_format($total, 2) }}</div>

  @if(!$isDead)
    <div class="rv-pay">
      <div class="rv-pay-track" role="img" aria-label="{{ $pct }}% paid"><div class="rv-pay-fill" style="width: {{ $pct }}%;"></div></div>
      <div class="rv-pay-row"><span>Paid ₱{{ number_format($paid, 0) }}</span><span>{{ $pct }}%</span></div>
      <div class="rv-pay-row"><span>Balance</span><b>₱{{ number_format($booking->remainingBalance(), 0) }}</b></div>
    </div>
  @endif

  <div class="rv-acts">
    @if($booking->status === 'approved' && $booking->reschedule_status !== 'pending' && $isUpcomingDate)
      <button type="button" class="rv-act rv-act-primary"
        onclick="openRescheduleModal({{ $booking->id }}, '{{ $booking->event_date->format('F d, Y') }}', '{{ $visit ? $visit->visit_date->format('F d, Y') : 'Not scheduled' }}', {{ $booking->reschedule_count }})">
        Reschedule
      </button>
    @endif
    @if($booking->canTransitionTo('cancelled') && !$booking->hasConfirmedPayment() && $isUpcomingDate)
      <form method="POST" action="{{ route('booking.cancel', $booking->id) }}" class="rv-cancel-form" data-kind="cancel"
        data-label="{{ $booking->package }}{{ $booking->room_number ? ' ' . $booking->room_number : '' }}"
        data-ref="#{{ $booking->booking_number }}"
        data-date="{{ $booking->event_date->format('F d, Y') }}"
        data-image="{{ \App\Models\Booking::packageImage($booking->package) }}"
        data-status="{{ $booking->status === 'pending' ? 'Pending approval' : ($booking->status === 'approved' ? 'Upcoming' : ucfirst($booking->status)) }}">
        @csrf
        <button type="submit" class="rv-act rv-act-danger">Cancel</button>
      </form>
    @endif
    @if($booking->customerCanDelete())
      {{-- Removes it from this list only; the admin keeps the record. --}}
      <form method="POST" action="{{ route('booking.delete', $booking->id) }}" class="rv-delete-form" data-kind="delete"
        data-label="{{ $booking->package }}{{ $booking->room_number ? ' ' . $booking->room_number : '' }}"
        data-ref="#{{ $booking->booking_number }}"
        data-date="{{ $booking->event_date->format('F d, Y') }}"
        data-image="{{ \App\Models\Booking::packageImage($booking->package) }}"
        data-status="{{ $isDead ? ucfirst($booking->status) : ($booking->status === 'completed' ? 'Completed' : 'Past') }}">
        @csrf
        <button type="submit" class="rv-act rv-act-danger">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="vertical-align: -1px; margin-right: 3px;"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/></svg>Delete
        </button>
      </form>
    @endif
  </div>
</div>
