<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'products';
    protected $fillable = [
        'name',
        'category_id',
        'unit_price',
        'image_url',
        'description',
        'type',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function category() {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class,'products_ingredients','product_id','ingredient_id');
    }

    public function meals()
    {
        return $this->belongsToMany(Meal::class,'meals_products','product_id','meal_id');
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
    public function productingredients()
{
    return $this->hasMany(ProductsIngredients::class, 'product_id');
}
}
