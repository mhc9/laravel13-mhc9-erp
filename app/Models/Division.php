<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $table = 'divisions';
    // protected $primaryKey = 'id';
    // public $incrementing = false; // false = ไม่ใช้ options auto increment
    // public $timestamps = false; // false = ไม่ใช้ field updated_at และ created_at

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}
