<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'recovery_rate',
        'status',
        'description',
    ];

    protected static function booted(): void
    {
        static::deleting(function (): void {
            throw new \LogicException('Rice types cannot be permanently deleted. Mark them inactive instead.');
        });
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function recoveryRateHistories()
    {
        return $this->hasMany(RiceTypeRecoveryRateHistory::class);
    }
}
