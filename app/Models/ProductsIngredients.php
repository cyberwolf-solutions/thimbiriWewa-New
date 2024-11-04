<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductsIngredients extends Model {
    use HasFactory;

    protected $table = 'products_ingredients';

    protected $fillable = [
        'product_id',
        'ingredient_id',
        'quantity',
        'created_by',
        'updated_by',
    ];
}
