<?php

namespace App\Exports;

use App\Services\DailySalesReportService;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportsExport implements FromArray, ShouldAutoSize, WithStyles
{
    private int $salesHeaderRow = 16;

    public function __construct(
        private readonly string $date,
        private readonly int|string $staffId = 'all'
    ) {}

    public function array(): array
    {
        $report = app(DailySalesReportService::class)->generate($this->date, $this->staffId);

        $rows = [
            ['PALAYTRACK DAILY SALES REPORT'],
            ['Paid Date', $this->date],
            ['Generated On', now()->format('Y-m-d h:i A')],
            [],
            ['SUMMARY'],
            ['Total Paid Sales', $report['totalIncome']],
            ['Menudo Sales', $report['menudoIncome']],
            ['Commercial Sales', $report['commercialIncome']],
            ['Cash Collections', $report['cashIncome']],
            ['Digital Collections', $report['digitalIncome']],
            ['Milling Subtotal', $report['subtotal']],
            ['Other Charges', $report['otherCharges']],
            ['Discounts', $report['discounts']],
            ['Total Palay Weight (kg)', $report['totalPalayWeight']],
            ['Total Transactions', $report['totalTransactions']],
            [
                'Client', 'Milling Type', 'Transactions', 'Total Palay Weight (kg)',
                'Fee/kg Used', 'Adjustment', 'Total Amount', 'Cashier',
            ],
        ];

        foreach ($report['groupedSales'] as $sale) {
            $feeUsed = (float) $sale->total_palay_weight > 0
                ? (float) $sale->subtotal / (float) $sale->total_palay_weight
                : 0;

            $rows[] = [
                $sale->client_name ?? 'Unknown Client',
                ucfirst($sale->milling_type),
                $sale->transaction_count,
                $sale->total_palay_weight,
                $feeUsed,
                '+₱' . number_format((float) $sale->other_charges, 2) . ' charges; -₱' . number_format((float) $sale->discount, 2) . ' discounts',
                $sale->total_amount,
                $sale->staff_name ?? 'Staff',
            ];
        }

        $rows[] = [];
        $rows[] = ['PAYMENT METHOD', 'TRANSACTIONS', 'AMOUNT'];

        foreach ($report['paymentBreakdown'] as $payment) {
            $rows[] = [
                strtoupper($payment->payment_method),
                $payment->transaction_count,
                $payment->total_amount,
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->mergeCells('A1:D1');

        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            5 => ['font' => ['bold' => true, 'size' => 13]],
            $this->salesHeaderRow => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2F5D1E']],
            ],
        ];
    }
}
