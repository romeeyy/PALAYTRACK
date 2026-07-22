<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Sales Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #172033; font-size: 10px; }
        h1 { text-align: center; margin: 0 0 4px; color: #234b18; }
        .meta { text-align: center; margin-bottom: 14px; color: #475569; }
        .summary { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .summary td { border: 1px solid #d7dee8; padding: 7px; width: 20%; }
        .summary strong { display: block; color: #475569; font-size: 8px; text-transform: uppercase; }
        table.data { width: 100%; border-collapse: collapse; }
        .data th { background: #2f5d1e; color: white; padding: 6px 4px; font-size: 8px; }
        .data td { border: 1px solid #d7dee8; padding: 5px 4px; }
        .right { text-align: right; }
        h2 { margin: 15px 0 6px; font-size: 13px; }
        .empty { text-align: center; padding: 16px; color: #64748b; }
        .adjustment { font-weight: bold; }
        .adjustment small { display: block; color: #64748b; font-weight: normal; margin-bottom: 3px; }
    </style>
</head>
<body>
    <h1>JK Diez Rice Mill</h1>
    <div class="meta">
        Daily Sales Report &mdash; {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}<br>
        Prepared By: {{ $preparedBy }} &nbsp; | &nbsp; Generated: {{ now()->format('F d, Y h:i A') }}
    </div>

    <table class="summary">
        <tr>
            <td><strong>Total Paid Sales</strong>₱{{ number_format($totalIncome, 2) }}</td>
            <td><strong>Cash</strong>₱{{ number_format($cashIncome, 2) }}</td>
            <td><strong>Digital</strong>₱{{ number_format($digitalIncome, 2) }}</td>
            <td><strong>Transactions</strong>{{ $totalTransactions }}</td>
            <td><strong>Palay Weight</strong>{{ number_format($totalPalayWeight, 2) }} kg</td>
        </tr>
        <tr>
            <td><strong>Menudo</strong>₱{{ number_format($menudoIncome, 2) }}</td>
            <td><strong>Commercial</strong>₱{{ number_format($commercialIncome, 2) }}</td>
            <td><strong>Subtotal</strong>₱{{ number_format($subtotal, 2) }}</td>
            <td><strong>Other Charges</strong>₱{{ number_format($otherCharges, 2) }}</td>
            <td><strong>Discounts</strong>₱{{ number_format($discounts, 2) }}</td>
        </tr>
    </table>

    <h2>Grouped Sales Breakdown</h2>
    <table class="data">
        <thead><tr>
            <th>Client</th><th>Type</th><th>Txns</th><th>Weight</th><th>Fee/kg</th>
            <th>Adjustment</th><th>Total Amount</th><th>Cashier</th>
        </tr></thead>
        <tbody>
        @forelse($groupedSales as $sale)
            @php($fee = $sale->total_palay_weight > 0 ? $sale->subtotal / $sale->total_palay_weight : 0)
            <tr>
                <td>{{ $sale->client_name ?? 'Unknown Client' }}</td>
                <td>{{ ucfirst($sale->milling_type) }}</td>
                <td class="right">{{ $sale->transaction_count }}</td>
                <td class="right">{{ number_format($sale->total_palay_weight, 2) }} kg</td>
                <td class="right">₱{{ number_format($fee, 2) }}</td>
                <td class="adjustment">
                    @if((float) $sale->other_charges > 0)+₱{{ number_format($sale->other_charges, 2) }}<small>Charge</small>@endif
                    @if((float) $sale->discount > 0)−₱{{ number_format($sale->discount, 2) }}<small>Discount</small>@endif
                    @if((float) $sale->other_charges == 0 && (float) $sale->discount == 0)None@endif
                </td>
                <td class="right">₱{{ number_format($sale->total_amount, 2) }}</td>
                <td>{{ $sale->staff_name ?? 'Staff' }}</td>
            </tr>
        @empty
            <tr><td colspan="8" class="empty">No paid sales found for this date.</td></tr>
        @endforelse
        </tbody>
    </table>

    <h2>Collections &amp; Adjustments</h2>
    <table class="summary">
        <tr>
            <td><strong>Cash Collections</strong>₱{{ number_format($cashIncome, 2) }}</td>
            <td><strong>Digital Collections</strong>₱{{ number_format($digitalIncome, 2) }}</td>
            <td><strong>Other Charges</strong>₱{{ number_format($otherCharges, 2) }}</td>
            <td><strong>Discounts</strong>₱{{ number_format($discounts, 2) }}</td>
        </tr>
    </table>
</body>
</html>
