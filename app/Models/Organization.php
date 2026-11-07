<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $table = 'organizations';

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hcode', 'hcode');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'organization_id', 'id');
    }

    public function members()
    {
        return $this->hasMany(Member::class, 'organization_id', 'id');
    }
}
