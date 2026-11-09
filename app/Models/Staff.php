<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // TODO: Import the Str facade for UUID

class Staff extends Model
{
    protected $table = 'staffs';

    /** Set primary key name manually if not id */
    // protected $primaryKey = 'id';
    
    /** TODO: The primary key type is a string */
    protected $keyType = 'string';

    /** TODO: false = ไม่ใช้ options auto increment */
    public $incrementing = false;

    /** false = ไม่ใช้ field updated_at และ created_at */
    // public $timestamps = false;

    /** Set particular field mass assignable */
    // protected $fillable = ['employee_id'];

    /** Set all the fields mass assignable */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'pid',
        'user_id',
        'created_at',
        'updated_at'
    ];

    /** TODO: Boot method to set the UUID automatically */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Check if the UUID is already set (e.g., if manually provided)
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid(); // Generate a new UUID
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
