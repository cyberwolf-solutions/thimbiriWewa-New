<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CustomerType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_types';
    protected $fillable = [
        'type',
        'description',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
