<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'customers';
    protected $fillable = [
        'name',
        'contact',
        'email',
        'address',
        'type',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
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
