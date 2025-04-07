<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerBoardMeal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_board_meals';
    protected $fillable = [
        'customer',
        'boarding',
        'mealtype',
        'booking',
        'date',
        'quantity',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
