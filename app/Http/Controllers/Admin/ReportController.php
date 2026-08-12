<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ReportController extends Controller
{
    /**
     * Show the reports page with filter form (empty state).
     */
    public function index()
    {
        return view('admin.reports');
    }

    /**
     * Generate a report based on filter parameters.
     */
    /**
     * Generate a report based on filter parameters.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:weekly,monthly,custom',
            'week_start'  => 'required_if:report_type,weekly|nullable|date',
            'month'       => 'required_if:report_type,monthly|nullable|integer|between:1,12',
            'year'        => 'required_if:report_type,monthly|nullable|integer|min:2020',
            'start_date'  => 'required_if:report_type,custom|nullable|date',
            'end_date'    => 'required_if:report_type,custom|nullable|date|after_or_equal:start_date',
        ]);

        // Determine date range
        [$startDate, $endDate] = $this->resolveDateRange($request);

        // Query bookings in the period (by event_date) from real database
        $bookings = Booking::with('user')
            ->whereBetween('event_date', [$startDate, $endDate])
            ->orderBy('event_date', 'asc')
            ->get();

        // Summary calculations
        $totalBookings = $bookings->count();

        $paidBookings = $bookings->whereIn('payment_status', ['partially_paid', 'fully_paid']);
        $totalRevenue = $paidBookings->sum('total_amount');

        $newUsers = User::where('role', '!=', 'admin')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->count();

        // Counts both rejected and customer/admin cancelled bookings.
        $cancellations = $bookings->filter(function ($b) {
            return in_array($b->status, [Booking::STATUS_CANCELLED, Booking::STATUS_REJECTED], true)
                || !is_null($b->reschedule_status);
        })->count();

        $summary = [
            'total_bookings'  => $totalBookings,
            'total_revenue'   => $totalRevenue,
            'new_users'       => $newUsers,
            'cancellations'   => $cancellations,
        ];

        // Chart data
        $chartData = $this->buildChartData($bookings, $startDate->copy(), $endDate->copy(), $request->report_type);

        // Preserve filter inputs for re-rendering
        $filters = $request->only(['report_type', 'week_start', 'month', 'year', 'start_date', 'end_date']);
        $filters['period_label'] = $this->buildPeriodLabel($startDate, $endDate, $request->report_type);

        return view('admin.reports', compact('bookings', 'summary', 'chartData', 'filters'));
    }

    /**
     * Export bookings table as premium Excel (.xlsx) file.
     */
    public function exportExcel(Request $request)
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);
        
        $bookings = Booking::with('user')
            ->whereBetween('event_date', [$startDate, $endDate])
            ->orderBy('event_date', 'asc')
            ->get();

        $periodLabel = $this->buildPeriodLabel($startDate, $endDate, $request->report_type);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Report');

        // Style base defaults
        $spreadsheet->getDefaultStyle()->getFont()->setName('Segoe UI')->setSize(11);

        // 1. Premium Title Rows
        $sheet->setCellValue('A1', "LorDane's Place — Report");
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true)->setColor(new Color('2C1A0E'));
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(35);

        $sheet->setCellValue('A2', "Period: " . $periodLabel);
        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A2')->getFont()->setSize(11)->setItalic(true)->setColor(new Color('8A6A40'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(2)->setRowHeight(22);

        // Empty separator row
        $sheet->getRowDimension(3)->setRowHeight(15);

        // 2. Table Headers (Row 4)
        $headers = ['Date', 'Customer Name', 'Package', 'Amount', 'Payment Status', 'Booking Status'];
        $colIndex = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($colIndex . '4', $header);
            $colIndex++;
        }

        // Header Style (Dark Brown #3D2817 background, Pale Cream/Gold #FFF9EF text)
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFF9EF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3D2817'],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ];
        $sheet->getStyle('A4:F4')->applyFromArray($headerStyle);
        $sheet->getRowDimension(4)->setRowHeight(28);

        // Header alignment overrides
        $sheet->getStyle('A4:C4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('D4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('E4:F4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // 3. Data Rows
        $row = 5;
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D4C4A0'], // Muted Gold border
                ],
            ],
        ];

        foreach ($bookings as $booking) {
            // Values
            $sheet->setCellValue('A' . $row, $booking->event_date->format('M d, Y'));
            $sheet->setCellValue('B' . $row, $booking->user->name ?? 'N/A');
            $sheet->setCellValue('C' . $row, $booking->package);
            $sheet->setCellValue('D' . $row, (float)($booking->total_amount ?? 0));
            $sheet->setCellValue('E' . $row, ucfirst(str_replace('_', ' ', $booking->payment_status ?? 'unpaid')));
            $sheet->setCellValue('F' . $row, ucfirst($booking->status));

            // Formatting & Borders
            $sheet->getStyle('A' . $row . ':F' . $row)->applyFromArray($borderStyle);

            // Alignment
            $sheet->getStyle('A' . $row . ':C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('E' . $row . ':F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Number formatting for amount (₱ currency symbol)
            $sheet->getStyle('D' . $row)->getNumberFormat()->setFormatCode('"₱"#,##0.00');

            // Zebra banding (alternating white and light cream)
            if ($row % 2 === 1) {
                $sheet->getStyle('A' . $row . ':F' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFDF9');
            } else {
                $sheet->getStyle('A' . $row . ':F' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFFFF');
            }

            $sheet->getRowDimension($row)->setRowHeight(20);
            $row++;
        }

        // 4. Totals Row
        $lastDataRow = $row - 1;
        $sheet->setCellValue('A' . $row, 'TOTAL REVENUE');
        $sheet->mergeCells('A' . $row . ':C' . $row);
        
        // Handle empty table formula safely to prevent excel #REF / sum range errors
        if ($lastDataRow < 5) {
            $sheet->setCellValue('D' . $row, 0.00);
        } else {
            $sheet->setCellValue('D' . $row, "=SUM(D5:D" . $lastDataRow . ")");
        }

        // Totals Row style
        $totalsStyle = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F5EDD8'], // Light Cream/Gold web matching footer
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D4C4A0'],
                ],
                'bottom' => [
                    'borderStyle' => Border::BORDER_DOUBLE,
                    'color' => ['rgb' => '3D2817'],
                ],
            ]
        ];
        $sheet->getStyle('A' . $row . ':F' . $row)->applyFromArray($totalsStyle);
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('D' . $row)->getNumberFormat()->setFormatCode('"₱"#,##0.00');
        $sheet->getRowDimension($row)->setRowHeight(24);

        // 5. Freeze Pane below header row
        $sheet->freezePane('A5');

        // 6. Autofit columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Build Response download stream
        $writer = new Xlsx($spreadsheet);
        $filename = 'lordanes_report_' . $startDate->format('Ymd') . '_to_' . $endDate->format('Ymd') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    // ───────────────────────── Helpers ─────────────────────────

    /**
     * Resolve the start and end Carbon dates from request parameters.
     */
    private function resolveDateRange(Request $request): array
    {
        switch ($request->report_type) {
            case 'weekly':
                $start = Carbon::parse($request->week_start)->startOfDay();
                $end   = $start->copy()->addDays(6)->endOfDay();
                break;

            case 'monthly':
                $start = Carbon::createFromDate($request->year, $request->month, 1)->startOfDay();
                $end   = $start->copy()->endOfMonth()->endOfDay();
                break;

            case 'custom':
            default:
                $start = Carbon::parse($request->start_date)->startOfDay();
                $end   = Carbon::parse($request->end_date)->endOfDay();
                break;
        }

        return [$start, $end];
    }

    /**
     * Build chart data arrays grouped by day or week.
     */
    private function buildChartData($bookings, Carbon $start, Carbon $end, string $type): array
    {
        $daysDiff = $start->diffInDays($end);
        $groupByWeek = ($type === 'monthly') || ($daysDiff > 14);

        $labels = [];
        $bookingCounts = [];
        $revenueTotals = [];

        if ($groupByWeek) {
            // Group by week
            $weekStart = $start->copy()->startOfWeek(Carbon::MONDAY);
            $weekEnd = $end->copy()->endOfWeek(Carbon::SUNDAY);

            $current = $weekStart->copy();
            while ($current->lte($weekEnd)) {
                $wStart = $current->copy();
                $wEnd   = $current->copy()->endOfWeek(Carbon::SUNDAY);

                $label = $wStart->format('M d') . ' – ' . $wEnd->format('M d');
                $labels[] = $label;

                $weekBookings = $bookings->filter(function ($b) use ($wStart, $wEnd) {
                    return $b->event_date->between($wStart, $wEnd);
                });

                $bookingCounts[] = $weekBookings->count();
                $revenueTotals[] = $weekBookings->whereIn('payment_status', ['partially_paid', 'fully_paid'])->sum('total_amount');

                $current->addWeek();
            }
        } else {
            // Group by day
            $period = CarbonPeriod::create($start, $end);
            foreach ($period as $date) {
                $labels[] = $date->format('M d');

                $dayBookings = $bookings->filter(function ($b) use ($date) {
                    return $b->event_date->isSameDay($date);
                });

                $bookingCounts[] = $dayBookings->count();
                $revenueTotals[] = $dayBookings->whereIn('payment_status', ['partially_paid', 'fully_paid'])->sum('total_amount');
            }
        }

        return [
            'labels'        => $labels,
            'bookingCounts' => $bookingCounts,
            'revenueTotals' => $revenueTotals,
        ];
    }

    /**
     * Human-readable label for the selected period.
     */
    private function buildPeriodLabel(Carbon $start, Carbon $end, string $type): string
    {
        return match ($type) {
            'weekly'  => 'Week of ' . $start->format('M d, Y'),
            'monthly' => $start->format('F Y'),
            'custom'  => $start->format('M d, Y') . ' — ' . $end->format('M d, Y'),
            default   => '',
        };
    }
}
