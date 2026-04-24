<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Receipt - {{ $booking->booking_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            color: #1a1208;
            margin: 0;
            padding: 40px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #B8860B;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #B8860B;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #666;
        }
        .receipt-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 30px;
            text-decoration: underline;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .details-table td {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .details-table td.label {
            font-weight: bold;
            width: 40%;
            color: #555;
        }
        .details-table td.value {
            text-align: right;
            font-weight: 500;
        }
        .summary-box {
            background-color: #fcfaf7;
            border: 1px solid #e5dace;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .amount-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .amount-label {
            display: table-cell;
            font-size: 16px;
        }
        .amount-value {
            display: table-cell;
            text-align: right;
            font-size: 18px;
            font-weight: bold;
        }
        .total-row {
            border-top: 2px solid #B8860B;
            padding-top: 10px;
            margin-top: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 60px;
            font-size: 14px;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .unpaid { background-color: #fee2e2; color: #ef4444; }
        .partially_paid { background-color: #dcfce7; color: #16a34a; }
        .fully_paid { background-color: #dbeafe; color: #2563eb; }
    </style>
</head>
<body>
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
                {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - 
                {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
            </td>
        </tr>
        @php $visit = $booking->visitSchedules->where('status', 'confirmed')->first(); @endphp
        @if($visit)
        <tr>
            <td class="label">Visit Schedule</td>
            <td class="value">{{ $visit->visit_date->format('F d, Y @ h:i A') }}</td>
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
            <div class="amount-label">Total Amount:</div>
            <div class="amount-value">₱{{ number_format($booking->total_amount, 2) }}</div>
        </div>
        <div class="amount-row">
            <div class="amount-label">Down Payment (20%):</div>
            <div class="amount-value">₱{{ number_format($booking->down_payment_amount, 2) }}</div>
        </div>
        <div class="amount-row total-row">
            <div class="amount-label" style="font-weight: bold;">Outstanding Balance:</div>
            <div class="amount-value">
                @if($booking->payment_status === 'fully_paid')
                    ₱0.00
                @elseif($booking->payment_status === 'partially_paid')
                    ₱{{ number_format($booking->total_amount - $booking->down_payment_amount, 2) }}
                @else
                    ₱{{ number_format($booking->total_amount, 2) }}
                @endif
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Thank you for choosing <strong>LorDane's Place</strong>!</p>
        <p>If you have any questions, please contact us at contact@lordanesplace.com</p>
        <p style="font-size: 10px; margin-top: 20px;">Generated on {{ now()->format('F d, Y h:i A') }}</p>
    </div>
</body>
</html>
