<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model {
    use HasFactory;
    protected $fillable = [
        'currency',
        'date_format',
        'time_format',
        'logo_light',
        'logo_dark',
        'title',
        'email',
        'contact',
        'invoice_prefix',
        'bill_prefix',
        'customer_prefix',
        'supplier_prefix',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function supplier($id) {
        return $this->supplier_prefix  . str_pad($id, 5, '0', STR_PAD_LEFT);
    }
    public function purchase($id) {
        return $this->bill_prefix  . str_pad($id, 5, '0', STR_PAD_LEFT);
    }
    public function invoice($id) {
        return $this->invoice_prefix  . str_pad($id, 5, '0', STR_PAD_LEFT);
    }
}
