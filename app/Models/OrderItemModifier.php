<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemModifier extends Model {
    use HasFactory;

    protected $table = 'order_items_modifiers';

    protected $fillable = [
        'item_id',
        'modifier_id',
        'price',
        'quantity',
        'total',
        'created_by',
        'updated_by',
    ];


    public function modifier() {
        return $this->hasOne(Modifier::class, 'id', 'modifier_id');
    }
}
