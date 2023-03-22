<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Courier extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
    ];

    public function deliveries(): HasMany{
        return $this->hasMany(Delivery::class);
    }
}
