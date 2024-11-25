<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $table = 'stock';

    protected $fillable = [
        'name',
        'quantity',
        'created_by',

    ];


    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'name', 'name');
    }
}
