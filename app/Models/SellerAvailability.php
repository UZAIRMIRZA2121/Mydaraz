<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerAvailability extends Model
{
    use HasFactory;
    
    protected $fillable = ['seller_id', 'day_of_week', 'start_time', 'end_time'];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}
