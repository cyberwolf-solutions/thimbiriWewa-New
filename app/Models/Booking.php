<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'bookings';
    protected $fillable = [
        'customer_id',
        'customer_type',
        'bording_type',
        'currency',
        'checkin',
        'checkout',
        'no_of_adults',
        'no_of_children',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function rooms() {
        return $this->belongsToMany(Room::class,'bookings_rooms','booking_id','room_id');
    }
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function customerss()
{
    return $this->belongsTo(Customer::class, 'customer_id');
}


    public function customers() {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
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
}