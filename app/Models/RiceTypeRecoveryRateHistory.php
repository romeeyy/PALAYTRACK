<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiceTypeRecoveryRateHistory extends Model
{
    protected $fillable = [
        'rice_type_id',
        'old_rate',
        'new_rate',
        'changed_by',
        'changed_at',
    ];

    protected $casts = [
        'old_rate' => 'decimal:2',
        'new_rate' => 'decimal:2',
        'changed_at' => 'datetime',
    ];

    public function riceType()
    {
        return $this->belongsTo(RiceType::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
