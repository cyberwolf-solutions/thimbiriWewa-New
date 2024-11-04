<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'ingredients';
    protected $fillable = [
        'name',
        'unit_price',
        'quantity',
        'min_quantity',
        'unit_id',
        'description',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function unit() {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class,'products_ingredients','ingredient_id','product_id');
    }

    public function modifiers()
    {
        return $this->belongsToMany(Modifier::class,'modifiers_ingredients','ingredient_id','modifier_id');
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
