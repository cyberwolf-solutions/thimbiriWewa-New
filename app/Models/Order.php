<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model {
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'customer_id',
        'room_id',
        'table_id',
        'orderable_id',
        'orderable_type',
        'order_date',
        'note',
        'type',
        'progress',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function customer() {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
    public function user() {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function table() {
        return $this->hasOne(Table::class, 'id', 'table_id');
    }
    public function room() {
        return $this->hasOne(Room::class, 'id', 'room_id');
    }
    public function payment() {
        return $this->hasOne(OrderPayment::class, 'order_id', 'id');
    }
    public function items() {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}
