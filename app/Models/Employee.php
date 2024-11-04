<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'employees';
    protected $fillable = [
        'fname',
        'lname',
        'contact_primary',
        'contact_secondary',
        'email',
        'nic',
        'address',
        'city',
        'emergency_name',
        'emergency_contact',
        'designation',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function designations() {
        return $this->hasOne(EmployeeDesignation::class, 'id', 'designation');
    }

    public function createdBy() {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updatedBy() {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function deletedBy() {
        return $this->hasOne(User::class, 'id', 'deleted_by');
    }
}
