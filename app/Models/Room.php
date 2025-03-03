<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'rooms';
    protected $fillable = [
        'name',
        'description',
        'room_no',
        'type',
        'image_url',
        'capacity',
        'size',
        'price',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'RoomFacility_id'
    ];
    public function pricings() {
        return $this->hasMany(RoomPricing::class);
    }
    
    public function type() {
        return $this->belongsTo(RoomType::class, 'type');
    }

    public function types() {
        return $this->hasOne(RoomType::class, 'id', 'type');
    }

    public function bookings() {
        return $this->belongsToMany(Booking::class,'bookings_rooms','room_id','booking_id');
    }

    public function createdBy() {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updatedBy() {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function deletedBy() {
        return $this->hasOne(User::class, 'id', 'deleted_by');
    }

    public function RoomFacility()
    {
        return $this->belongsTo(RoomFacilities::class, 'RoomFacility_id');
    }

    
}
