<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MillingFeeHistory extends Model
{
    protected $fillable = [
        'milling_type',
        'old_fee',
        'new_fee',
        'changed_by',
        'changed_at',
    ];
    public function user()
{
    return $this->belongsTo(User::class, 'changed_by');
}
}
