<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MealsProducts extends Model {
    use HasFactory;

    protected $table = 'meals_products';
    protected $fillable = [
        'meal_id',
        'product_id',
        'quantity',
        'created_by',
        'updated_by',
    ];
}
