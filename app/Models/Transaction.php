<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'delivery_id',
        'user_id',
        'milling_type',
        'milling_fee_per_kg',
        'palay_weight_kg',
        'subtotal',
        'other_charges',
        'discount',
        'total_amount',
        'payment_method',
        'amount_received',
        'change_amount',
        'reference_number',
        'payment_status',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
