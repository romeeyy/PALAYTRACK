@extends('layouts.staff')

@section('content')
<style>
    .page-header {
        margin-bottom: 28px;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 900;
        color: #0f172a;
        margin: 0 0 6px;
    }

    .page-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin: 0;
    }

    .summary-card,
    .section-card {
        background: #ffffff;
        border: none;
        border-radius: 22px;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
    }

    .summary-card {
        padding: 22px 22px 20px;
        height: 100%;
        border-top: none;
        background: linear-gradient(135deg, #ffffff 0%, #f4faf3 100%);
        position: relative;
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .summary-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 32px rgba(15, 23, 42, 0.12);
    }

    .summary-card.pending {
        background: linear-gradient(135deg, #ffffff 0%, #fffdf0 100%);
    }

    .summary-card.processing {
        background: linear-gradient(135deg, #ffffff 0%, #f3f7ff 100%);
    }

    .summary-card.completed {
        background: linear-gradient(135deg, #ffffff 0%, #f4faf3 100%);
    }

    .summary-card.claimed {
        background: linear-gradient(135deg, #ffffff 0%, #f0fdfa 100%);
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

    .summary-card.pending::after {
        background: rgba(234, 179, 8, 0.12);
    }

    .summary-card.processing::after {
        background: rgba(59, 130, 246, 0.10);
    }

    .summary-card.completed::after {
        background: rgba(34, 197, 94, 0.10);
    }

    .summary-card.claimed::after {
        background: rgba(15, 118, 110, 0.10);
    }

    .summary-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 18px;
        position: relative;
        z-index: 1;
    }

    .summary-label {
        color: #334155;
        font-size: 0.98rem;
        font-weight: 800;
        line-height: 1.5;
        margin: 0;
    }

    .summary-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: #eef6ea;
        color: #2f5d1e;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .summary-icon svg {
        width: 22px;
        height: 22px;
        stroke-width: 2.2;
    }

    .summary-card.pending .summary-icon {
        background: #fef9c3;
        color: #ca8a04;
    }

    .summary-card.processing .summary-icon {
        background: #dbeafe;
        color: #2563eb;
    }

    .summary-card.completed .summary-icon {
        background: #dcfce7;
        color: #15803d;
    }

    .summary-card.claimed .summary-icon {
        background: #ccfbf1;
        color: #0f766e;
    }

    .summary-value {
        font-size: 2.35rem;
        font-weight: 900;
        color: #0f172a;
        line-height: 1;
        margin: 0;
        position: relative;
        z-index: 1;
        display: flex;
        align-items: baseline;
    }

    .summary-note {
        color: #64748b;
        font-size: 0.95rem;
        margin: 8px 0 0;
        position: relative;
        z-index: 1;
    }

    .section-card {
        padding: 22px;
        height: 100%;
        background: linear-gradient(135deg, #ffffff 0%, #fbfdf9 100%);
    }

    .operations-card {
        padding: 24px;
        height: auto;
        min-height: 0;
        background: linear-gradient(135deg, #ffffff 0%, #fbfdf9 100%);
    }

    .operations-grid {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(280px, 0.9fr);
        gap: 20px;
        align-items: stretch;
    }

    .operations-trend {
        min-width: 0;
        padding: 20px;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        background: #ffffff;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
    }

    .operations-today {
        min-width: 0;
        padding: 4px 0;
    }

    .section-title {
        font-size: 1.15rem;
        font-weight: 900;
        color: #111827;
        margin-bottom: 6px;
    }

    .section-subtitle {
        color: #64748b;
        font-size: 0.95rem;
        margin-bottom: 18px;
    }

    .updated-pill {
        background: #f1f5f9;
        color: #475569;
        font-size: 0.78rem;
        font-weight: 800;
        padding: 8px 12px;
        border-radius: 999px;
        white-space: nowrap;
    }

    .chart-box {
        height: 270px;
    }

    .chart-insight {
        margin-top: 14px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .insight-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 999px;
        background: #eef6ea;
        color: #2f5d1e;
        font-size: 0.9rem;
        font-weight: 900;
    }

    .today-summary-list {
        display: grid;
        gap: 10px;
        margin-top: 16px;
    }

    .today-summary-item {
        min-height: 92px;
        padding: 15px 16px;
        border-radius: 16px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .today-summary-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(15, 23, 42, 0.07);
    }

    .today-summary-value {
        margin-top: 7px;
        font-size: 1.65rem;
        font-weight: 900;
        color: #2f5d1e;
        line-height: 1;
    }

    .today-summary-label {
        margin: 0;
        color: #64748b;
        font-size: 0.84rem;
        font-weight: 700;
    }

    .today-summary-icon {
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #15803d;
        background: #dcfce7;
    }

    .today-summary-icon.processing {
        color: #2563eb;
        background: #dbeafe;
    }

    .today-summary-icon.ready {
        color: #0f766e;
        background: #ccfbf1;
    }

    .today-summary-icon svg {
        width: 20px;
        height: 20px;
    }

    .table-scroll {
        max-height: 330px;
        overflow-y: auto;
        overflow-x: auto;
        border-radius: 14px;
    }

    .soft-table {
        margin-bottom: 0;
    }

    .soft-table thead th {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        color: #334155;
        font-weight: 800;
        white-space: nowrap;
        padding: 14px 12px;
        font-size: 0.92rem;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .soft-table tbody td {
        border-bottom: 1px solid #eef2f7;
        padding: 12px 12px;
        vertical-align: middle;
        font-size: 0.95rem;
        color: #334155;
    }

    .soft-table tbody tr:hover {
        background: #f8fafc;
    }

    .queue-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 44px;
        padding: 6px 11px;
        border-radius: 999px;
        background: #ecfdf3;
        color: #15803d;
        font-weight: 800;
        font-size: 0.9rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 13px;
        border-radius: 999px;
        font-size: 0.84rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-pending {
        background: #fef3c7;
        color: #a16207;
    }

    .status-processing {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-completed {
        background: #dcfce7;
        color: #15803d;
    }

    .status-claimed {
        background: #ccfbf1;
        color: #0f766e;
    }

    .open-btn {
        border-radius: 10px;
        padding: 7px 14px;
        font-weight: 700;
        white-space: nowrap;
    }

    .empty-state {
        color: #64748b;
        text-align: center;
        padding: 45px 0;
        font-size: 0.95rem;
    }

    .section-footer {
        margin-top: 16px;
        display: flex;
        justify-content: flex-end;
    }

    .view-link {
        font-size: 0.92rem;
        font-weight: 800;
        color: #16a34a;
        text-decoration: none;
    }

    .view-link:hover {
        color: #15803d;
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 1.75rem;
        }

        .summary-value {
            font-size: 2rem;
        }

        .section-card {
            padding: 18px;
        }

        .table-scroll {
            max-height: 260px;
        }

        .chart-box {
            height: 250px;
        }
    }

    @media (max-width: 1199px) {
        .operations-grid {
            grid-template-columns: 1fr;
        }

        .operations-trend {
            padding: 18px;
        }
    }

    html[data-theme="dark"] .operations-trend {
        border-color: #334155;
        background: #172235;
    }
</style>

@php
    $activeQueue = \App\Models\Delivery::whereIn('status', ['pending', 'processing'])
        ->latest()
        ->take(5)
        ->get();

    $safeTrendLabels = $trendLabels ?? [];
    $safeTrendCounts = $trendCounts ?? [];

    $peakCount = count($safeTrendCounts) ? max($safeTrendCounts) : 0;
    $peakIndex = count($safeTrendCounts) ? array_search($peakCount, $safeTrendCounts) : null;
    $peakLabel = $peakIndex !== null && isset($safeTrendLabels[$peakIndex]) ? $safeTrendLabels[$peakIndex] : 'N/A';
@endphp

<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Overview of queue status and daily milling operations.</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="summary-card pending">
            <div class="summary-top">
                <p class="summary-label">Pending This Month</p>
                <div class="summary-icon">
                    <i data-lucide="clock-3"></i>
                </div>
            </div>
            <h2 class="summary-value">{{ $pendingCount }}</h2>
            <p class="summary-note">Waiting to be processed</p>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="summary-card processing">
            <div class="summary-top">
                <p class="summary-label">Processing This Month</p>
                <div class="summary-icon">
                    <i data-lucide="settings-2"></i>
                </div>
            </div>
            <h2 class="summary-value">{{ $processingCount }}</h2>
            <p class="summary-note">Currently in milling</p>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="summary-card completed">
            <div class="summary-top">
                <p class="summary-label">Completed This Month</p>
                <div class="summary-icon">
                    <i data-lucide="check-check"></i>
                </div>
            </div>
            <h2 class="summary-value">{{ $completedCount }}</h2>
            <p class="summary-note">Ready for claim</p>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="summary-card claimed">
            <div class="summary-top">
                <p class="summary-label">Claimed This Month</p>
                <div class="summary-icon">
                    <i data-lucide="badge-check"></i>
                </div>
            </div>
            <h2 class="summary-value">{{ $claimedCount }}</h2>
            <p class="summary-note">Released to customer</p>
        </div>
    </div>
</div>

<div class="section-card operations-card mb-4">
    <div class="operations-grid">
        <section class="operations-trend">
            <h2 class="section-title">Daily Deliveries Trend</h2>
            <p class="section-subtitle">Deliveries recorded over the last 7 days.</p>

            <div class="chart-box">
                <canvas id="deliveryTrendChart"></canvas>
            </div>

            <div class="chart-insight">
                <div class="insight-pill">
                    <i data-lucide="activity"></i>
                    Peak: {{ $peakLabel }} ({{ $peakCount }} {{ $peakCount == 1 ? 'delivery' : 'deliveries' }})
                </div>
            </div>
        </section>

        <aside class="operations-today">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                <div>
                    <h2 class="section-title">Today’s Summary</h2>
                    <p class="section-subtitle mb-0">Quick operational view for today.</p>
                </div>

                <div class="updated-pill">
                    Updated: {{ now()->format('M d, Y • h:i A') }}
                </div>
            </div>

            <div class="today-summary-list">
                <div class="today-summary-item">
                    <div>
                        <div class="today-summary-label">Deliveries today</div>
                        <div class="today-summary-value">{{ $todayDeliveries->count() }}</div>
                    </div>
                    <div class="today-summary-icon">
                        <i data-lucide="truck"></i>
                    </div>
                </div>

                <div class="today-summary-item">
                    <div>
                        <div class="today-summary-label">Currently processing</div>
                        <div class="today-summary-value">{{ $processingCount }}</div>
                    </div>
                    <div class="today-summary-icon processing">
                        <i data-lucide="settings-2"></i>
                    </div>
                </div>

                <div class="today-summary-item">
                    <div>
                        <div class="today-summary-label">Ready for claim</div>
                        <div class="today-summary-value">{{ $completedCount }}</div>
                    </div>
                    <div class="today-summary-icon ready">
                        <i data-lucide="badge-check"></i>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="section-card">
            <h2 class="section-title">Active Queue</h2>
            <p class="section-subtitle">All pending and processing deliveries that still need staff action.</p>

            <div class="table-responsive table-scroll">
                <table class="table soft-table align-middle">
                    <thead>
                        <tr>
                            <th>Queue #</th>
                            <th>Delivery ID</th>
                            <th>Client</th>
                            <th>Rice Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($activeQueue as $delivery)
                            <tr>
                                <td>
                                    <span class="queue-badge">#{{ $delivery->queue_number }}</span>
                                </td>
                                <td>{{ $delivery->delivery_id }}</td>
                                <td>{{ $delivery->client_name }}</td>
                                <td>{{ $delivery->riceType->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="status-badge status-{{ $delivery->status }}">
                                        {{ ucfirst($delivery->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ url('/staff/delivery-details/' . $delivery->id) }}"
                                       class="btn btn-outline-success btn-sm open-btn">
                                        Open
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">
                                    No active queue items.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($activeQueue->count() > 0)
                <div class="section-footer">
                    <a href="{{ route('staff.deliveries') }}" class="view-link">
                        View all deliveries
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    if (window.lucide) {
        lucide.createIcons();
    }

    const trendLabels = JSON.parse('{!! json_encode($trendLabels ?? []) !!}');
    const trendCounts = JSON.parse('{!! json_encode($trendCounts ?? []) !!}');

    const deliveryTrendChart = document.getElementById('deliveryTrendChart');

    if (deliveryTrendChart) {
        new Chart(deliveryTrendChart, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Deliveries',
                    data: trendCounts,
                    borderColor: '#2f5d1e',
                    backgroundColor: 'rgba(47, 93, 30, 0.10)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#2f5d1e',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Deliveries',
                            color: '#334155',
                            font: {
                                weight: '700'
                            }
                        },
                        ticks: {
                            precision: 0,
                            color: '#475569'
                        },
                        grid: {
                            color: '#e5e7eb'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#475569'
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw + ' deliveries';
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endsection
