<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchases extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'supplier_id',
        'note',
        'sub_total',
        'vat',
        'vat_amount',
        'discount',
        'total',
        'payment_status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public function supplier() {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }
    public function items() {
        return $this->hasMany(PurchaseItem::class, 'purchase_id', 'id');
    }

    public function payments() {
        return $this->hasMany(PurchasePayment::class, 'purchase_id', 'id');
    }

    public function paymentSum() {

        return $this->payments()->sum('amount');
    }

    public function calculateDueAmount() {

        return $this->total - $this->payments()->sum('amount');
    }
}
