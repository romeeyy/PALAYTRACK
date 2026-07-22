<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientHistory extends Model
{
    protected $fillable = ['client_id', 'owner_id', 'field', 'old_value', 'new_value', 'changed_at'];
    protected $casts = ['changed_at' => 'datetime'];
    public function client() { return $this->belongsTo(Client::class); }
    public function owner() { return $this->belongsTo(User::class, 'owner_id'); }
}
