<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reports | LorDane's Place Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    /* ── Print Stylesheet ── */
    @page {
      size: landscape;
      margin: 12mm;
    }

    @media print {
      /* Reset page background and layout */
      html, body {
        background: #fff !important;
        display: block !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }

      /* Hide non-print elements */
      .no-print { display: none !important; }

      /* Hide sidebar AND the spacer div (spacer has no class, target by inline style) */
      aside,
      div[style*="width: 260px"][style*="flex-shrink: 0"] {
        display: none !important;
        width: 0 !important;
      }

      /* Main content: fill full page width */
      main {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        min-height: auto !important;
        overflow: visible !important;
      }

      /* Print header */
      .print-header {
        display: block !important;
        margin-bottom: 16px !important;
        padding-bottom: 10px !important;
      }
      .print-header h1 { font-size: 20px !important; }
      .print-header p { font-size: 12px !important; }

      /* Summary cards: all 4 in one row, compact */
      .summary-grid {
        grid-template-columns: repeat(4, 1fr) !important;
        gap: 10px !important;
        margin-bottom: 18px !important;
      }
      .summary-grid > div {
        padding: 14px 10px !important;
        border-radius: 6px !important;
      }
      .summary-grid > div h3 {
        font-size: 28px !important;
      }
      .summary-grid > div p {
        font-size: 9px !important;
        letter-spacing: 2px !important;
        margin-top: 6px !important;
      }

      /* Report cards: avoid page breaks inside */
      .report-card {
        break-inside: avoid;
        page-break-inside: avoid;
      }

      /* Bookings table: compact for print */
      .report-card,
      div[style*="border-radius: 10px"][style*="overflow: hidden"] {
        border-radius: 4px !important;
        margin-bottom: 14px !important;
      }

      #reportTable {
        width: 100% !important;
        table-layout: fixed !important;
      }
      #reportTable th {
        padding: 8px 8px !important;
        font-size: 9px !important;
        letter-spacing: 1px !important;
      }
      #reportTable td {
        padding: 8px 8px !important;
        font-size: 11px !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
      }

      /* Table header bar */
      div[style*="border-bottom: 1px solid #d4c4a0"][style*="padding: 20px 28px"] {
        padding: 10px 12px !important;
      }

      /* Table footer totals bar */
      div[style*="border-top: 2px solid #d4c4a0"] {
        padding: 10px 12px !important;
      }

      /* Badges: slightly smaller */
      .badge {
        padding: 2px 8px !important;
        font-size: 9px !important;
      }
    }

    /* ── Status Badges ── */
    .badge { display: inline-block; padding: 4px 14px; border-radius: 100px; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; }
    .badge-approved { background: #d4edda; color: #28a745; border: 1px solid #28a745; }
    .badge-pending { background: #fff3cd; color: #856404; border: 1px solid #d4a017; }
    .badge-rejected { background: #f8d7da; color: #c0392b; border: 1px solid #e74c3c; }

    .badge-paid { background: #d4edda; color: #28a745; border: 1px solid #28a745; }
    .badge-partial { background: #fff3cd; color: #856404; border: 1px solid #d4a017; }
    .badge-unpaid { background: #f5f0e8; color: #8a6a40; border: 1px solid #d4c4a0; }

    /* ── Filters ── */
    .filter-select, .filter-input {
      background: #fff9ef;
      border: 1px solid #d4c4a0;
      color: #2c1a0e;
      padding: 11px 16px;
      border-radius: 6px;
      font-size: 14px;
      font-family: 'Jost', sans-serif;
      outline: none;
      transition: border-color 0.2s;
    }
    .filter-select:focus, .filter-input:focus {
      border-color: #c9a84c;
    }

    /* ── Gold Button ── */
    .btn-gold {
      background: linear-gradient(135deg, #c9a84c, #b8952e);
      color: #fff;
      border: none;
      padding: 12px 32px;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 700;
      letter-spacing: 1px;
      cursor: pointer;
      font-family: 'Jost', sans-serif;
      transition: all 0.3s;
      text-transform: uppercase;
    }
    .btn-gold:hover {
      background: linear-gradient(135deg, #d4b65c, #c9a84c);
      box-shadow: 0 4px 15px rgba(201, 168, 76, 0.35);
      transform: translateY(-1px);
    }

    /* ── Export Button ── */
    .btn-export {
      background: transparent;
      border: 1px solid #c9a84c;
      color: #c9a84c;
      padding: 10px 24px;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
      font-family: 'Jost', sans-serif;
      transition: all 0.2s;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    .btn-export:hover {
      background: #c9a84c;
      color: #2c1a0e;
    }

    /* ── Summary Cards Grid ── */
    .summary-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
      margin-bottom: 32px;
    }
    @media (max-width: 1100px) {
      .summary-grid { grid-template-columns: repeat(2, 1fr); }
    }

    /* ── Chart Container ── */
    .chart-container {
      position: relative;
      width: 100%;
      max-height: 340px;
    }
  </style>
</head>
<body style="margin: 0; font-family: 'Jost', sans-serif; background: #f5f0e8; min-height: 100vh; display: flex;">

  @include('partials._admin-sidebar')

  {{-- MAIN CONTENT --}}
  <main style="flex: 1; padding: 40px 48px; min-height: 100vh; overflow-y: auto;">

    {{-- Print-only header --}}
    <div class="print-header" style="display: none; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid #c9a84c;">
      <h1 style="font-family: 'Cormorant Garamond', serif; font-size: 28px; font-weight: 700; color: #2c1a0e; margin: 0;">LorDane's Place — Report</h1>
      @isset($filters)
        <p style="font-size: 14px; color: #8a6a40; margin: 4px 0 0;">{{ $filters['period_label'] ?? '' }}</p>
      @endisset
    </div>

    {{-- Page Header --}}
    <div style="margin-bottom: 32px;" class="no-print">
      <h1 style="font-family: 'Cormorant Garamond', serif; font-size: 38px; font-weight: 700; color: #2c1a0e; margin: 0 0 4px;">Reports</h1>
      <p style="font-size: 12px; letter-spacing: 3px; color: #8a6a40; text-transform: uppercase; font-weight: 600; margin: 0;">Generate booking and revenue reports</p>
    </div>

    {{-- ═══════════ FILTER CARD ═══════════ --}}
    <div class="no-print" style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; padding: 28px; margin-bottom: 32px;">
      <h2 style="font-size: 13px; letter-spacing: 3px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0 0 20px;">Generate Report</h2>

      <form method="POST" action="{{ route('admin.reports.generate') }}" id="reportForm">
        @csrf
        <div style="display: flex; flex-wrap: wrap; align-items: flex-end; gap: 16px;">

          {{-- Report Type --}}
          <div>
            <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700; margin-bottom: 6px; text-transform: uppercase;">Report Type</label>
            <select name="report_type" id="reportType" class="filter-select" onchange="toggleFilters()" style="min-width: 180px;">
              <option value="weekly" {{ (isset($filters) && $filters['report_type'] === 'weekly') ? 'selected' : '' }}>Weekly</option>
              <option value="monthly" {{ (isset($filters) && $filters['report_type'] === 'monthly') ? 'selected' : '' }}>Monthly</option>
              <option value="custom" {{ (isset($filters) && $filters['report_type'] === 'custom') ? 'selected' : '' }}>Custom Range</option>
            </select>
          </div>

          {{-- Weekly: week picker --}}
          <div id="weeklyFields">
            <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700; margin-bottom: 6px; text-transform: uppercase;">Week Starting</label>
            <input type="date" name="week_start" class="filter-input" value="{{ $filters['week_start'] ?? now()->startOfWeek(\Carbon\Carbon::MONDAY)->format('Y-m-d') }}" style="min-width: 180px;" />
          </div>

          {{-- Monthly: month + year --}}
          <div id="monthlyFields" style="display: none;">
            <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700; margin-bottom: 6px; text-transform: uppercase;">Month</label>
            <div style="display: flex; gap: 10px;">
              <select name="month" class="filter-select">
                @for($m = 1; $m <= 12; $m++)
                  <option value="{{ $m }}" {{ (isset($filters) && (int)($filters['month'] ?? 0) === $m) ? 'selected' : ((!isset($filters) && $m === (int)now()->format('m')) ? 'selected' : '') }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
              </select>
              <select name="year" class="filter-select">
                @for($y = now()->year; $y >= 2024; $y--)
                  <option value="{{ $y }}" {{ (isset($filters) && (int)($filters['year'] ?? 0) === $y) ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
              </select>
            </div>
          </div>

          {{-- Custom: start + end --}}
          <div id="customFields" style="display: none;">
            <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700; margin-bottom: 6px; text-transform: uppercase;">Date Range</label>
            <div style="display: flex; gap: 10px; align-items: center;">
              <input type="date" name="start_date" class="filter-input" value="{{ $filters['start_date'] ?? '' }}" />
              <span style="color: #8a6a40; font-weight: 600;">to</span>
              <input type="date" name="end_date" class="filter-input" value="{{ $filters['end_date'] ?? '' }}" />
            </div>
          </div>

          {{-- Submit --}}
          <div>
            <button type="submit" class="btn-gold">
              <span style="display: inline-flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Generate Report
              </span>
            </button>
          </div>
        </div>

        @if($errors->any())
          <div style="margin-top: 16px; background: #f8d7da; border: 1px solid #e74c3c; color: #c0392b; padding: 12px 18px; border-radius: 6px; font-size: 14px;">
            @foreach($errors->all() as $error)
              <p style="margin: 0;">{{ $error }}</p>
            @endforeach
          </div>
        @endif
      </form>
    </div>

    {{-- ═══════════ REPORT RESULTS ═══════════ --}}
    @isset($bookings)

      {{-- Period Label --}}
      <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div>
          <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 28px; font-weight: 700; color: #2c1a0e; margin: 0;">{{ $filters['period_label'] ?? 'Report' }}</h2>
          <p style="font-size: 13px; color: #8a6a40; margin: 4px 0 0;">Generated on {{ now()->format('M d, Y \a\t h:i A') }}</p>
        </div>

        {{-- Export Buttons --}}
        <div class="no-print" style="display: flex; gap: 12px;">
          <button onclick="window.print()" class="btn-export">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Export as PDF
          </button>
          <form method="POST" action="{{ route('admin.reports.export.excel') }}" style="margin: 0;">
            @csrf
            <input type="hidden" name="report_type" value="{{ $filters['report_type'] }}" />
            <input type="hidden" name="week_start" value="{{ $filters['week_start'] ?? '' }}" />
            <input type="hidden" name="month" value="{{ $filters['month'] ?? '' }}" />
            <input type="hidden" name="year" value="{{ $filters['year'] ?? '' }}" />
            <input type="hidden" name="start_date" value="{{ $filters['start_date'] ?? '' }}" />
            <input type="hidden" name="end_date" value="{{ $filters['end_date'] ?? '' }}" />
            <button type="submit" class="btn-export">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/></svg>
              Export as Excel
            </button>
          </form>
        </div>
      </div>

      {{-- ── Summary Cards ── --}}
      <div class="summary-grid report-card">
        <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; padding: 28px 24px; text-align: center;">
          <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 48px; font-weight: 700; color: #c9a84c; margin: 0; line-height: 1;">{{ $summary['total_bookings'] }}</h3>
          <p style="font-size: 11px; letter-spacing: 3px; color: #8a6a40; margin: 10px 0 0; text-transform: uppercase; font-weight: 700;">Total Bookings</p>
        </div>
        <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; padding: 28px 24px; text-align: center;">
          <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 48px; font-weight: 700; color: #c9a84c; margin: 0; line-height: 1;">₱{{ number_format($summary['total_revenue'], 0) }}</h3>
          <p style="font-size: 11px; letter-spacing: 3px; color: #8a6a40; margin: 10px 0 0; text-transform: uppercase; font-weight: 700;">Total Revenue</p>
        </div>
        <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; padding: 28px 24px; text-align: center;">
          <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 48px; font-weight: 700; color: #c9a84c; margin: 0; line-height: 1;">{{ $summary['new_users'] }}</h3>
          <p style="font-size: 11px; letter-spacing: 3px; color: #8a6a40; margin: 10px 0 0; text-transform: uppercase; font-weight: 700;">New Users</p>
        </div>
        <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; padding: 28px 24px; text-align: center;">
          <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 48px; font-weight: 700; color: #c9a84c; margin: 0; line-height: 1;">{{ $summary['cancellations'] }}</h3>
          <p style="font-size: 11px; letter-spacing: 3px; color: #8a6a40; margin: 10px 0 0; text-transform: uppercase; font-weight: 700;">Cancel / Resched</p>
        </div>
      </div>

      {{-- ── Chart ── --}}
      <div class="no-print" style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; padding: 28px; margin-bottom: 32px;">
        <h2 style="font-size: 13px; letter-spacing: 3px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0 0 20px;">Trend Overview</h2>
        <div class="chart-container">
          <canvas id="reportChart"></canvas>
        </div>
      </div>

      {{-- ── Bookings Table ── --}}
      <div class="report-card" style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; overflow: hidden; margin-bottom: 32px;">
        <div style="padding: 20px 28px; border-bottom: 1px solid #d4c4a0; display: flex; justify-content: space-between; align-items: center;">
          <h2 style="font-size: 13px; letter-spacing: 3px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0;">Booking Details</h2>
          <span style="font-size: 13px; color: #8a6a40;">{{ $bookings->count() }} record{{ $bookings->count() !== 1 ? 's' : '' }}</span>
        </div>

        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse;" id="reportTable">
            <thead>
              <tr style="border-bottom: 1px solid #d4c4a0;">
                <th style="padding: 14px 24px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">DATE</th>
                <th style="padding: 14px 24px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">CUSTOMER</th>
                <th style="padding: 14px 24px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">PACKAGE</th>
                <th style="padding: 14px 24px; text-align: right; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">AMOUNT</th>
                <th style="padding: 14px 24px; text-align: center; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">PAYMENT</th>
                <th style="padding: 14px 24px; text-align: center; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">STATUS</th>
              </tr>
            </thead>
            <tbody>
              @forelse($bookings as $booking)
                <tr style="border-bottom: 1px solid #e8dcc8; transition: background 0.2s;" onmouseover="this.style.background='#f5edd8'" onmouseout="this.style.background='transparent'">
                  <td style="padding: 16px 24px; font-size: 14px; color: #2c1a0e;">{{ $booking->event_date->format('M d, Y') }}</td>
                  <td style="padding: 16px 24px; font-size: 14px; color: #2c1a0e; font-weight: 600;">
                    {{ $booking->user->name ?? 'N/A' }}
                    @if($booking->booking_number)
                      <br><small style="color: #8a6a40; font-size: 11px; font-weight: normal;">{{ $booking->booking_number }}</small>
                    @endif
                  </td>
                  <td style="padding: 16px 24px; font-size: 14px; color: #8a6a40;">{{ $booking->package }}</td>
                  <td style="padding: 16px 24px; font-size: 14px; color: #2c1a0e; text-align: right; font-weight: 600;">₱{{ number_format($booking->total_amount ?? 0, 2) }}</td>
                  <td style="padding: 16px 24px; text-align: center;">
                    @php $ps = $booking->payment_status ?? 'unpaid'; @endphp
                    <span class="badge {{ $ps === 'fully_paid' ? 'badge-paid' : ($ps === 'partially_paid' ? 'badge-partial' : 'badge-unpaid') }}">
                      {{ $ps === 'fully_paid' ? 'Fully Paid' : ($ps === 'partially_paid' ? 'Partial' : 'Unpaid') }}
                    </span>
                  </td>
                  <td style="padding: 16px 24px; text-align: center;">
                    <span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" style="text-align: center; padding: 60px; color: #8a6a40; font-size: 16px;">No bookings found in this period.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Table Footer: Totals --}}
        @if($bookings->count() > 0)
          <div style="padding: 16px 24px; border-top: 2px solid #d4c4a0; display: flex; justify-content: flex-end; gap: 32px; background: #f5edd8;">
            <span style="font-size: 13px; color: #8a6a40; font-weight: 700; letter-spacing: 1px;">TOTAL REVENUE</span>
            <span style="font-size: 15px; color: #2c1a0e; font-weight: 800;">₱{{ number_format($summary['total_revenue'], 2) }}</span>
          </div>
        @endif
      </div>

    @endisset

  </main>

  {{-- ═══════════ JAVASCRIPT ═══════════ --}}
  <script>
    // ── Toggle filter fields based on report type ──
    function toggleFilters() {
      const type = document.getElementById('reportType').value;
      document.getElementById('weeklyFields').style.display  = type === 'weekly'  ? '' : 'none';
      document.getElementById('monthlyFields').style.display  = type === 'monthly' ? '' : 'none';
      document.getElementById('customFields').style.display   = type === 'custom'  ? '' : 'none';
    }
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', toggleFilters);

    @isset($chartData)
    // ── Chart.js Bar Chart ──
    document.addEventListener('DOMContentLoaded', function () {
      const ctx = document.getElementById('reportChart').getContext('2d');

      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: @json($chartData['labels']),
          datasets: [
            {
              label: 'Bookings',
              data: @json($chartData['bookingCounts']),
              backgroundColor: 'rgba(201, 168, 76, 0.7)',
              borderColor: '#c9a84c',
              borderWidth: 1,
              borderRadius: 4,
              yAxisID: 'y',
              order: 2,
            },
            {
              label: 'Revenue (₱)',
              data: @json($chartData['revenueTotals']),
              type: 'line',
              borderColor: '#8a6a40',
              backgroundColor: 'rgba(138, 106, 64, 0.1)',
              borderWidth: 2,
              pointRadius: 4,
              pointBackgroundColor: '#8a6a40',
              fill: true,
              tension: 0.3,
              yAxisID: 'y1',
              order: 1,
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: { mode: 'index', intersect: false },
          plugins: {
            legend: {
              labels: {
                font: { family: "'Jost', sans-serif", size: 12 },
                color: '#2c1a0e',
                usePointStyle: true,
                pointStyle: 'circle',
                padding: 20,
              }
            },
            tooltip: {
              backgroundColor: '#2c1a0e',
              titleFont: { family: "'Jost', sans-serif" },
              bodyFont: { family: "'Jost', sans-serif" },
              callbacks: {
                label: function (context) {
                  if (context.dataset.label === 'Revenue (₱)') {
                    return 'Revenue: ₱' + Number(context.raw).toLocaleString();
                  }
                  return context.dataset.label + ': ' + context.raw;
                }
              }
            }
          },
          scales: {
            x: {
              grid: { display: false },
              ticks: {
                font: { family: "'Jost', sans-serif", size: 11 },
                color: '#8a6a40',
                maxRotation: 45,
              }
            },
            y: {
              position: 'left',
              beginAtZero: true,
              title: {
                display: true,
                text: 'Bookings',
                font: { family: "'Jost', sans-serif", size: 12, weight: '600' },
                color: '#c9a84c',
              },
              ticks: {
                stepSize: 1,
                font: { family: "'Jost', sans-serif", size: 11 },
                color: '#8a6a40',
              },
              grid: { color: 'rgba(212, 196, 160, 0.3)' },
            },
            y1: {
              position: 'right',
              beginAtZero: true,
              title: {
                display: true,
                text: 'Revenue (₱)',
                font: { family: "'Jost', sans-serif", size: 12, weight: '600' },
                color: '#8a6a40',
              },
              ticks: {
                font: { family: "'Jost', sans-serif", size: 11 },
                color: '#8a6a40',
                callback: function (value) {
                  return '₱' + Number(value).toLocaleString();
                }
              },
              grid: { drawOnChartArea: false },
            }
          }
        }
      });
    });
    @endisset
  </script>

</body>
</html>
