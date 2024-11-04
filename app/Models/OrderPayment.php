<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model {
    use HasFactory;

    protected $table = 'orders_payments';

    protected $fillable = [
        'order_id',
        'date',
        'sub_total',
        'vat',
        'discount',
        'total',
        'payment_type',
        'description',
        'payment_status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function purchase() {
        return $this->belongsTo(Purchases::class, 'purchase_id');
    }
}
