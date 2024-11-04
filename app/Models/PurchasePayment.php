<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasePayment extends Model {
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'date',
        'amount',
        'reference',
        'description',
        'receipt',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
