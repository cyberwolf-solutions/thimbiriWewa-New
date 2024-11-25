<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyStock extends Model
{
    use HasFactory;
    protected $table = 'daily_stock';

    protected $fillable = [
        'name',
        'quantity',
        'products',
        'created_by',
        'date'

    ];


    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'name', 'name');
    }
}
