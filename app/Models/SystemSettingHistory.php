<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSettingHistory extends Model
{
    protected $fillable = ['key', 'old_value', 'new_value', 'changed_by', 'changed_at'];
    protected $casts = ['changed_at' => 'datetime'];
    public function user() { return $this->belongsTo(User::class, 'changed_by'); }
}
