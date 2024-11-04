<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomType extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'room_types';
    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
        'deleted_by',
        // 'RoomFacility_id'
    ];

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
