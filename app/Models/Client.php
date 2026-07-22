<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_number',
        'client_type',
    ];

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function isCommercial()
    {
        return $this->client_type === 'commercial';
    }
}