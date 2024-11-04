<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailConfig extends Model {
    use HasFactory;
    protected $fillable = [
        'driver',
        'host',
        'port',
        'from_address',
        'from_name',
        'username',
        'password',
        'encryption',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
