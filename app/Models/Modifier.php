<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modifier extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'modifiers';
    protected $fillable = [
        'name',
        'unit_price',
        'description',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class,'modifiers_ingredients','modifier_id','ingredient_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class,'modifiers_categories','modifier_id','category_id');
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
