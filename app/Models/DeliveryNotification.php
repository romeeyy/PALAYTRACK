<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Delivery;

class DeliveryNotification extends Model
{
    protected $fillable = [
        'delivery_id',
        'method',
        'notification_status',
        'remarks',
        'notified_at',
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }
}