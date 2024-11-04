<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomFacilities extends Model
{
    use HasFactory;

    protected $table = 'room_facilities';

    protected $fillable = [
        'List',
        'name',
        'room_facilities',
        'created_by',
        
    ];

    public function roomtype()
    {
        return $this->hasMany(Room::class, 'RoomFacility_id');
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
