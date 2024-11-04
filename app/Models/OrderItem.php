<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {
    use HasFactory;

    protected $fillable = [
        'order_id',
        'itemable_id',
        'itemable_type',
        'price',
        'quantity',
        'total',
        'created_by',
        'updated_by',
    ];

    public function meal() {
        return $this->hasOne(Meal::class, 'id', 'itemable_id');
    }

    public function modifiers() {
        return $this->hasMany(OrderItemModifier::class, 'item_id', 'id');
    }
}
