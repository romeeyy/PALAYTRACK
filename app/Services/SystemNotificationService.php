<?php

namespace App\Services;

use App\Models\Delivery;
use App\Models\DeliveryNotification;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Collection;

class SystemNotificationService
{
    public function forUser(User $user): Collection
    {
        $items = collect();
        $since = now()->subDays(30);

        if ($user->isOwner()) {
            Transaction::with('delivery')
                ->whereNotNull('paid_at')
                ->where('paid_at', '>=', $since)
                ->latest('paid_at')
                ->limit(8)
                ->get()
                ->each(function (Transaction $transaction) use ($items): void {
                    $client = $transaction->delivery?->client_name ?? 'Client';
                    $items->push($this->item(
                        "payment-{$transaction->id}",
                        'Payment recorded',
                        "{$client} paid ₱" . number_format((float) $transaction->total_amount, 2) . '.',
                        $transaction->paid_at,
                        route('owner.payment-records'),
                        'receipt'
                    ));
                });
        } else {
            Delivery::where('status', 'pending')
                ->where('staff_id', $user->id)
                ->where('created_at', '>=', $since)
                ->latest()
                ->limit(6)
                ->get()
                ->each(function (Delivery $delivery) use ($items): void {
                    $items->push($this->item(
                        "delivery-{$delivery->id}",
                        'New delivery',
                        "Queue #{$delivery->queue_number} · {$delivery->client_name}",
                        $delivery->created_at,
                        route('staff.delivery-details', $delivery->id),
                        'truck'
                    ));
                });
        }

        $completedDeliveries = Delivery::where('status', 'completed')
            ->whereHas('transaction', fn ($query) => $query->where('payment_status', 'paid'))
            ->whereHas('notifications', fn ($query) => $query->whereIn('notification_status', ['sent', 'reached']))
            ->whereNotNull('completed_at')
            ->where('completed_at', '>=', $since);

        if (!$user->isOwner()) {
            $completedDeliveries->where('staff_id', $user->id);
        }

        $completedDeliveries
            ->latest('completed_at')
            ->limit(8)
            ->get()
            ->each(function (Delivery $delivery) use ($items, $user): void {
                $items->push($this->item(
                    "completed-{$delivery->id}",
                    'Ready for claiming',
                    "Queue #{$delivery->queue_number} · {$delivery->client_name}",
                    $delivery->completed_at,
                    route($user->isOwner() ? 'owner.delivery-details' : 'staff.delivery-details', $delivery->id),
                    'circle-check'
                ));
            });

        $failedNotifications = DeliveryNotification::with('delivery')
            ->where('notification_status', 'failed')
            ->where('created_at', '>=', $since);

        if (!$user->isOwner()) {
            $failedNotifications->whereHas('delivery', function ($query) use ($user): void {
                $query->where('staff_id', $user->id);
            });
        }

        $failedNotifications
            ->latest()
            ->limit(8)
            ->get()
            ->each(function (DeliveryNotification $notification) use ($items, $user): void {
                if (!$notification->delivery) {
                    return;
                }

                $items->push($this->item(
                    "sms-failed-{$notification->id}",
                    'SMS notification failed',
                    "Queue #{$notification->delivery->queue_number} · {$notification->delivery->client_name}",
                    $notification->created_at,
                    route($user->isOwner() ? 'owner.delivery-details' : 'staff.delivery-details', $notification->delivery->id),
                    'triangle-alert',
                    'danger'
                ));
            });

        $readAt = $user->notifications_read_at ?? $user->created_at;

        return $items
            ->filter(fn (array $item) => $item['date'])
            ->sortByDesc('date')
            ->unique('key')
            ->take(8)
            ->map(function (array $item) use ($readAt): array {
                $item['unread'] = !$readAt || $item['date']->isAfter($readAt);
                return $item;
            })
            ->values();
    }

    private function item(
        string $key,
        string $title,
        string $message,
        $date,
        string $url,
        string $icon,
        string $tone = 'default'
    ): array {
        return compact('key', 'title', 'message', 'date', 'url', 'icon', 'tone');
    }
}
