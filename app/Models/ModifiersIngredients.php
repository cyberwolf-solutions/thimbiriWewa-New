<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModifiersIngredients extends Model {
    use HasFactory;

    protected $table = 'modifiers_ingredients';

    protected $fillable = [
        'modifier_id',
        'ingredient_id',
        'quantity',
        'created_by',
        'updated_by',
    ];
}
