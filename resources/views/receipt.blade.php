<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Receipt - {{ $booking->booking_number }}</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #1a1208;
            margin: 0;
            padding: 0;
            font-size: 12px;
            line-height: 1.45;
        }
        /* The PDF page itself is receipt-sized (see ReceiptController), so the
           slip fills the sheet edge to edge instead of floating on blank paper. */
        .receipt {
            width: 100%;
            margin: 0;
            padding: 16px 18px;
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #B8860B;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }
        .header h1 {
            margin: 0;
            color: #B8860B;
            font-size: 17px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 3px 0 0;
            font-size: 10px;
            color: #777;
        }
        .receipt-title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1.5px;
            margin-bottom: 14px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .details-table td {
            padding: 4px 0;
            font-size: 11px;
            vertical-align: top;
        }
        .details-table td.label {
            color: #666;
            width: 42%;
        }
        .details-table td.value {
            text-align: right;
            font-weight: 600;
        }
        .summary-box {
            border-top: 1px dashed #B8860B;
            margin-top: 10px;
            padding-top: 10px;
        }
        .amount-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
            font-size: 11px;
        }
        .amount-label {
            display: table-cell;
            color: #555;
        }
        .amount-value {
            display: table-cell;
            text-align: right;
            font-weight: 700;
            /* DejaVu Sans kept here so the peso sign (₱) renders in the PDF; Arial/Helvetica lack that glyph. */
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
        }
        .total-row {
            border-top: 1px dashed #B8860B;
            padding-top: 7px;
            margin-top: 6px;
        }
        .total-row .amount-label,
        .total-row .amount-value {
            font-size: 13px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 16px;
            padding-top: 10px;
            border-top: 1px dashed #ccc;
            font-size: 9.5px;
            color: #888;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .unpaid { background-color: #fee2e2; color: #ef4444; }
        .partially_paid { background-color: #dcfce7; color: #16a34a; }
        .fully_paid { background-color: #dbeafe; color: #2563eb; }

        @media print {
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>LorDane's Place</h1>
            <p>Your Premier Event Destination</p>
            <p>Booking Acknowledgment & Receipt</p>
        </div>

        <div class="receipt-title">BOOKING RECEIPT</div>

        <table class="details-table">
            <tr>
                <td class="label">Booking Number</td>
                <td class="value">{{ $booking->booking_number }}</td>
            </tr>
            <tr>
                <td class="label">Client Name</td>
                <td class="value">{{ $booking->user->name }}</td>
            </tr>
            <tr>
                <td class="label">Event Type</td>
                <td class="value">{{ $booking->event_type }}</td>
            </tr>
            <tr>
                <td class="label">Package</td>
                <td class="value">{{ $booking->package }}</td>
            </tr>
            <tr>
                <td class="label">Event Date</td>
                <td class="value">{{ $booking->event_date->format('F d, Y') }}</td>
            </tr>
            <tr>
                <td class="label">Time Slot</td>
                <td class="value">
                    @if($booking->start_time && $booking->end_time)
                        {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} -
                        {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                    @else
                        To be finalized
                    @endif
                </td>
            </tr>
            @php $visit = $booking->visitSchedules->where('status', 'confirmed')->first(); @endphp
            @if($visit)
            <tr>
                <td class="label">Visit Schedule</td>
                <td class="value">{{ $visit->visit_date->format('M d, Y @ h:i A') }}</td>
            </tr>
            @endif
            <tr>
                <td class="label">Date Booked</td>
                <td class="value">{{ $booking->created_at->format('F d, Y') }}</td>
            </tr>
            <tr>
                <td class="label">Payment Status</td>
                <td class="value">
                    <span class="status-badge {{ $booking->payment_status }}">
                        {{ str_replace('_', ' ', $booking->payment_status) }}
                    </span>
                </td>
            </tr>
        </table>

        <div class="summary-box">
            <div class="amount-row">
                <div class="amount-label">Total Booking Amount</div>
                <div class="amount-value">₱{{ number_format($booking->total_amount, 2) }}</div>
            </div>
            <div class="amount-row">
                <div class="amount-label">Payment Type</div>
                <div class="amount-value">{{ \App\Models\Booking::paymentOptionLabel($booking->payment_option) }}</div>
            </div>
            <div class="amount-row">
                <div class="amount-label">Amount Paid</div>
                <div class="amount-value">₱{{ number_format($booking->amountPaid(), 2) }}</div>
            </div>
            <div class="amount-row total-row">
                <div class="amount-label">Remaining Balance</div>
                <div class="amount-value">₱{{ number_format($booking->remainingBalance(), 2) }}</div>
            </div>
        </div>

        <div class="footer">
            <p style="margin:0;">Thank you for choosing <strong>LorDane's Place</strong>!</p>
            <p style="margin:3px 0 0;">0917 745 5049 &middot; lordanesplace@gmail.com</p>
            <p style="margin:6px 0 0;">Generated on {{ now()->format('F d, Y h:i A') }}</p>
        </div>
    </div>
</body>
</html>
