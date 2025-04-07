<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buffet extends Model
{
    use HasFactory;

    protected $table = 'buffets';
    protected $fillable = [
        'name',
        'price',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
