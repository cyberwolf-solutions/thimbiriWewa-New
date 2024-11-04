<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model {
    use HasFactory;
    protected $fillable = [
        'purchase_id',
        'product_id',
        'price',
        'quantity',
        'total',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function purchase() {
        return $this->belongsTo(Purchases::class, 'id', 'purchase_id');
    }
    // public function product() {
    //     return $this->hasOne(Ingredient::class, 'id', 'product_id');
    // }
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
