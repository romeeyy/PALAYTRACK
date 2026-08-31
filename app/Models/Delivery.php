<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_id',
        'staff_id',
        'queue_number',
        'queue_date',
        'client_name',
        'client_id',
        'client_type_at_delivery',
        'milling_type_at_delivery',
        'milling_fee_per_kg_at_delivery',
        'contact_number',
        'rice_type_id',
        'sacks',
        'palay_weight',
        'recovery_rate',
        'estimated_rice',
        'actual_rice',
        'status',
        'notes',
        'delivered_at',
        'completed_at',
        'claimed_at',
    ];

    protected $casts = [
        'queue_date' => 'date',
        'delivered_at' => 'datetime',
        'completed_at' => 'datetime',
        'claimed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Delivery $delivery): void {
            $delivery->queue_date ??= ($delivery->delivered_at ?? now())->toDateString();
        });

        static::saving(function (Delivery $delivery): void {
            if (!$delivery->isDirty('status')) {
                return;
            }

            if ($delivery->status === 'completed' && !$delivery->completed_at) {
                $delivery->completed_at = now();
            }

            if ($delivery->status === 'claimed') {
                $delivery->completed_at ??= now();
                $delivery->claimed_at ??= now();
            }
        });
    }

    public function riceType()
    {
        return $this->belongsTo(RiceType::class);
    }

    public function notifications()
    {
        return $this->hasMany(DeliveryNotification::class);
    }

    public function hasSuccessfulNotification(): bool
    {
        if ($this->relationLoaded('notifications')) {
            return $this->notifications->contains(
                fn (DeliveryNotification $notification) => in_array(
                    $notification->notification_status,
                    ['sent', 'reached'],
                    true
                )
            );
        }

        return $this->notifications()
            ->whereIn('notification_status', ['sent', 'reached'])
            ->exists();
    }

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
