@extends('layouts.owner')

@section('content')

<style>
    .page-header {
        margin-bottom: 16px;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .page-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin: 0;
    }

    .summary-card {
        border: none;
        border-radius: 22px;
        padding: 18px 18px 16px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        height: 100%;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #ffffff 0%, #f4faf3 100%);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        border-top: 5px solid #15803d;
    }

    .summary-card.orange {
        background: linear-gradient(135deg, #ffffff 0%, #fffaf0 100%);
        border-top-color: #f59e0b;
    }

    .summary-card.yellow {
        background: linear-gradient(135deg, #ffffff 0%, #fffdf0 100%);
        border-top-color: #eab308;
    }

    .summary-card.blue {
        background: linear-gradient(135deg, #ffffff 0%, #f3f7ff 100%);
        border-top-color: #3b82f6;
    }

    .summary-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 32px rgba(15, 23, 42, 0.12);
    }

    .summary-card::after {
        content: "";
        position: absolute;
        right: -35px;
        bottom: -35px;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: rgba(47, 93, 30, 0.08);
    }

    .summary-card.orange::after {
        background: rgba(245, 158, 11, 0.12);
    }

    .summary-card.yellow::after {
        background: rgba(234, 179, 8, 0.12);
    }

    .summary-card.blue::after {
        background: rgba(59, 130, 246, 0.10);
    }

    .summary-top,
    .summary-value {
        position: relative;
        z-index: 1;
    }

    .summary-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 12px;
    }

    .summary-label {
        font-size: 0.98rem;
        color: #334155;
        font-weight: 800;
        line-height: 1.5;
        margin: 0;
    }

    .summary-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: #eef6ea;
        color: #2f5d1e;
    }

    .summary-card.orange .summary-icon {
        background: #fff7ed;
        color: #ea580c;
    }

    .summary-card.yellow .summary-icon {
        background: #fef9c3;
        color: #ca8a04;
    }

    .summary-card.blue .summary-icon {
        background: #e8f0ff;
        color: #2563eb;
    }

    .summary-icon svg {
        width: 22px;
        height: 22px;
        stroke-width: 2.2;
    }

    .summary-value {
        font-size: 2.35rem;
        font-weight: 900;
        color: #0f172a;
        line-height: 1;
        margin: 0;
        display: flex;
        align-items: baseline;
        flex-wrap: wrap;
        gap: 4px;
    }

    .main-content .summary-card .summary-value {
        font-weight: 900;
    }

    .summary-unit {
        font-size: 0.98rem;
        color: #475569;
        font-weight: 700;
    }

    .insight-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fbf7 100%);
        border: 1px solid #edf2f7;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        margin-bottom: 28px;
    }

    .insight-header {
        margin-bottom: 14px;
    }

    .main-content .insight-card .insight-title {
        font-size: 1.35rem;
        font-weight: 900;
        color: #0f172a;
        margin: 0 0 6px;
        line-height: 1.2;
    }

    .insight-subtitle {
        color: #64748b;
        font-size: 0.95rem;
        margin: 0;
    }

    .last-updated {
        background: #f1f5f9;
        color: #475569;
        font-size: 0.82rem;
        font-weight: 800;
        padding: 9px 13px;
        border-radius: 999px;
    }

    .revenue-panel {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        padding: 20px;
        height: 100%;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.04);
    }

    .revenue-panel-header {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: flex-start;
        margin-bottom: 18px;
    }

    .revenue-panel-header h5 {
        font-size: 1.1rem;
        font-weight: 900;
        color: #0f172a;
        margin: 0 0 5px;
    }

    .revenue-panel-header p {
        color: #64748b;
        margin: 0;
        font-size: 0.9rem;
    }

    .revenue-total {
        text-align: right;
        font-size: 1.35rem;
        font-weight: 900;
        color: #15803d;
        white-space: nowrap;
    }

    .revenue-total span {
        display: block;
        color: #64748b;
        font-size: 0.78rem;
        font-weight: 800;
        margin-top: 3px;
    }

    .revenue-chart-wrap {
        position: relative;
        height: 290px;
    }

    .quick-insight-stack {
        display: grid;
        gap: 14px;
        height: 100%;
    }

    .quick-insight-item {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        padding: 17px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.04);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .quick-insight-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 24px rgba(15, 23, 42, 0.10);
    }

    .quick-insight-item span {
        display: block;
        font-size: 0.82rem;
        color: #64748b;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .quick-insight-item h4 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 900;
        color: #0f172a;
    }

    .quick-insight-item h4 small {
        font-size: 0.82rem;
        color: #64748b;
        font-weight: 800;
    }

    .quick-insight-item svg {
        width: 38px;
        height: 38px;
        padding: 9px;
        border-radius: 13px;
        flex-shrink: 0;
    }

    .quick-insight-item.green svg {
        background: #dcfce7;
        color: #15803d;
    }

    .quick-insight-item.orange svg {
        background: #ffedd5;
        color: #ea580c;
    }

    .quick-insight-item.blue svg {
        background: #dbeafe;
        color: #2563eb;
    }

    .quick-insight-item.yellow svg {
        background: #fef9c3;
        color: #ca8a04;
    }

    .section-header {
        margin-bottom: 24px;
    }

    .section-title {
        font-size: 1.7rem;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 6px;
        line-height: 1.2;
    }

    .section-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin: 0;
    }

    .chart-card {
        border: none;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        background: #ffffff;
    }

    .chart-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 32px rgba(15, 23, 42, 0.12);
    }

    .chart-card-header {
        background: linear-gradient(135deg, #ffffff 0%, #f8fbf7 100%);
        color: #111827;
        padding: 20px 22px 14px;
        border-bottom: 1px solid #eef2f7;
    }

    .chart-card-header h5 {
        font-size: 1.2rem;
        font-weight: 900;
        margin: 0 0 6px;
        line-height: 1.3;
    }

    .chart-card-header p {
        margin: 0;
        color: #64748b;
        font-size: 0.95rem;
    }

    .chart-card-body {
        background: #ffffff;
        padding: 22px;
        min-height: 360px;
    }

    .chart-wrap {
        position: relative;
        height: 300px;
    }

    .alert-soft {
        border: none;
        border-radius: 14px;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.05);
    }

    @media (max-width: 992px) {
        .summary-value {
            font-size: 2rem;
        }

        .revenue-panel-header {
            flex-direction: column;
        }

        .revenue-total {
            text-align: left;
        }
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 1.75rem;
        }

        .section-title {
            font-size: 1.45rem;
        }

        .chart-card-body {
            min-height: 300px;
            padding: 18px;
        }

        .chart-wrap {
            height: 240px;
        }

        .revenue-chart-wrap {
            height: 240px;
        }
    }
</style>

<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Overview of inventory, deliveries, and milling activity.</p>
</div>

@if(session('success'))
<div class="alert alert-success alert-soft mb-4">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-soft mb-4">
    <ul class="mb-0 ps-3">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card summary-card green">
            <div class="summary-top">
                <p class="summary-label">Total Palay Inventory</p>
                <div class="summary-icon">
                    <i data-lucide="leaf"></i>
                </div>
            </div>
            <h2 class="summary-value">
                {{ number_format($totalPalayInventory, 2) }}
                <span class="summary-unit">kg</span>
            </h2>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card summary-card orange">
            <div class="summary-top">
                <p class="summary-label">Total Milled Rice Inventory</p>
                <div class="summary-icon">
                    <i data-lucide="package"></i>
                </div>
            </div>
            <h2 class="summary-value">
                {{ number_format($totalMilledRiceInventory, 2) }}
                <span class="summary-unit">kg</span>
            </h2>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card summary-card yellow">
            <div class="summary-top">
                <p class="summary-label">Pending Deliveries</p>
                <div class="summary-icon">
                    <i data-lucide="clock-3"></i>
                </div>
            </div>
            <h2 class="summary-value">{{ $pendingDeliveries }}</h2>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card summary-card blue">
            <div class="summary-top">
                <p class="summary-label">Completed Deliveries</p>
                <div class="summary-icon">
                    <i data-lucide="badge-check"></i>
                </div>
            </div>
            <h2 class="summary-value">{{ $completedDeliveries }}</h2>
        </div>
    </div>
</div>

<div class="insight-card">
    <div class="insight-header d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h2 class="insight-title">Business Insights</h2>
            <p class="insight-subtitle">Revenue performance and milling service summary.</p>
        </div>
        <div class="last-updated">
            Updated: {{ now()->format('M d, Y • h:i A') }}
        </div>
    </div>

    <div class="row g-4 align-items-stretch">
        <div class="col-lg-8">
            <div class="revenue-panel">
                <div class="revenue-panel-header">
                    <div>
                        <h5>7-Day Revenue Trend</h5>
                        <p>Menudo vs Commercial income performance</p>
                    </div>
                    <div class="revenue-total">
                        ₱{{ number_format($sevenDayIncome, 2) }}
                        <span>7-Day Total Income</span>
                    </div>
                </div>

                <div class="revenue-chart-wrap">
                    <canvas id="revenueTrendChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="quick-insight-stack">
                <div class="quick-insight-item green">
                    <div>
                        <span>This Month’s Income</span>
                        <h4>₱{{ number_format($monthlyIncome, 2) }}</h4>
                    </div>
                    <i data-lucide="wallet"></i>
                </div>

                <div class="quick-insight-item orange">
                    <div>
                        <span>Today’s Income</span>
                        <h4>₱{{ number_format($todayIncome, 2) }}</h4>
                    </div>
                    <i data-lucide="trending-up"></i>
                </div>

                <div class="quick-insight-item blue">
                    <div>
                        <span>This Month’s Most Used Milling Type</span>
                        <h4>
                            {{ $mostUsed }}
                            @if($mostUsedPercent > 0)
                            <small>{{ $mostUsedPercent }}%</small>
                            @endif
                        </h4>
                    </div>
                    <i data-lucide="activity"></i>
                </div>

                <div class="quick-insight-item yellow">
                    <div>
                        <span>This Month’s Transactions</span>
                        <h4>{{ $monthlyTransactions }}</h4>
                    </div>
                    <i data-lucide="receipt-text"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="insight-card" aria-labelledby="analytics-title">
    <div class="section-header">
        <h2 id="analytics-title" class="insight-title">Analytics</h2>
        <p class="section-subtitle">Production and operational insights.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card chart-card">
                <div class="chart-card-header">
                    <h5>This Month Milled Rice Production</h5>
                    <p>Rice production overview for {{ now()->format('F Y') }}</p>
                </div>
                <div class="chart-card-body">
                    <div class="chart-wrap">
                        <canvas id="productionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card chart-card">
                <div class="chart-card-header">
                    <h5>This Month Delivery Status Distribution</h5>
                    <p>Current status of deliveries received in {{ now()->format('F Y') }}</p>
                </div>
                <div class="chart-card-body">
                    <div class="chart-wrap">
                        <canvas id="deliveryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script id="revenue-trend-data" type="application/json">
    @json([
        'labels' => $revenueTrendLabels,
        'menudo' => $menudoRevenueTrend,
        'commercial' => $commercialRevenueTrend
    ])
</script>

<script id="production-data" type="application/json">
    @json([
        'labels' => $productionLabels,
        'data' => $productionData
    ])
</script>

<script id="delivery-data" type="application/json">
    @json([
        'labels' => $deliveryLabels,
        'data' => $deliveryData
    ])
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const revenueTrendPayload = JSON.parse(
        document.getElementById('revenue-trend-data').textContent
    );

    const productionPayload = JSON.parse(
        document.getElementById('production-data').textContent
    );

    const deliveryPayload = JSON.parse(
        document.getElementById('delivery-data').textContent
    );

    new Chart(document.getElementById('revenueTrendChart'), {
        type: 'line',
        data: {
            labels: revenueTrendPayload.labels,
            datasets: [{
                    label: 'Menudo Income',
                    data: revenueTrendPayload.menudo,
                    borderColor: '#15803d',
                    backgroundColor: 'rgba(21, 128, 61, 0.10)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6
                },
                {
                    label: 'Commercial Income',
                    data: revenueTrendPayload.commercial,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.10)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            animation: {
                duration: 1100,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#334155',
                        boxWidth: 14,
                        padding: 16,
                        font: {
                            size: 13,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ₱' + Number(context.raw).toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e5e7eb'
                    },
                    ticks: {
                        color: '#475569',
                        callback: function(value) {
                            return '₱' + Number(value).toLocaleString();
                        }
                    }
                },
                x: {
                    ticks: {
                        color: '#334155'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('productionChart'), {
        type: 'bar',
        data: {
            labels: productionPayload.labels,
            datasets: [{
                label: 'Kilograms (kg)',
                data: productionPayload.data,
                backgroundColor: [
                    '#166534',
                    '#15803d',
                    '#16a34a',
                    '#22c55e',
                    '#86efac'
                ],
                borderRadius: 10,
                borderSkipped: false
            }]
        },
        options: {
            maintainAspectRatio: false,
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e5e7eb'
                    },
                    ticks: {
                        color: '#475569'
                    },
                    title: {
                        display: true,
                        text: 'Kilograms (kg)',
                        color: '#334155',
                        font: {
                            size: 14,
                            weight: '600'
                        }
                    }
                },
                x: {
                    ticks: {
                        color: '#334155'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('deliveryChart'), {
        type: 'doughnut',
        data: {
            labels: deliveryPayload.labels,
            datasets: [{
                data: deliveryPayload.data,
                backgroundColor: ['#eab308', '#3b82f6', '#22c55e', '#15803d'],
                borderColor: '#ffffff',
                borderWidth: 4,
                hoverOffset: 6
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#334155',
                        boxWidth: 14,
                        padding: 16,
                        font: {
                            size: 14
                        }
                    }
                }
            }
        }
    });

    if (window.lucide) {
        lucide.createIcons();
    }
</script>

@endsection