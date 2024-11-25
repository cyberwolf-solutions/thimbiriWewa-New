<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BordingType extends Model
{
    use HasFactory;

    protected $table = 'bording_type';

    protected $fillable = [
        'name',
        'price',
        'created_by',

    ];

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function updatedBy()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function deletedBy()
    {
        return $this->hasOne(User::class, 'id', 'deleted_by');
    }
}
