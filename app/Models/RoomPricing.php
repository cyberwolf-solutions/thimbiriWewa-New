<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomPricing extends Model {
    use HasFactory;

    protected $table = 'room_pricings';
    protected $fillable = ['room_id', 'boarding_type_id', 'customer_type_id', 'rate','currency'];

    public function room() {
        return $this->belongsTo(Room::class);
    }

    public function boardingType() {
        return $this->belongsTo(BordingType::class);
    }

    public function customerType() {
        return $this->belongsTo(CustomerType::class);
    }
}

