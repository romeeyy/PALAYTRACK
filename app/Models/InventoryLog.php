<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Delivery;

class InventoryLog extends Model
{
    protected $fillable = [
        'delivery_id',
        'stock_category',
        'type',
        'quantity',
        'remarks',
        'logged_at',
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }
}