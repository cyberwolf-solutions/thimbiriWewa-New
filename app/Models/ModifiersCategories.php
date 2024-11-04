<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModifiersCategories extends Model {
    use HasFactory;

    protected $table = 'modifiers_categories';

    protected $fillable = [
        'modifier_id',
        'category_id',
        'created_by',
        'updated_by',
    ];

    public function modifier() {
        return $this->hasOne(Modifier::class, 'id', 'modifier_id');
    }
}
