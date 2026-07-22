<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffAccountHistory extends Model
{
    protected $fillable = [
        'staff_id', 'owner_id', 'action', 'field', 'old_value', 'new_value', 'changed_at',
    ];

    protected $casts = ['changed_at' => 'datetime'];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
