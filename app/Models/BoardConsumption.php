<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class BoardConsumption extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'board_consumptions';
    protected $fillable = [
        'half_board',
        'full_board',
        'bb',
        'date',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
