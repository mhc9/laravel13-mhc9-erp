<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Scope;

class Employee extends Model
{
    protected $table = 'employees';
    // protected $primaryKey = 'id';
    // public $incrementing = false; // false = ไม่ใช้ options auto increment
    // public $timestamps = false; // false = ไม่ใช้ field updated_at และ created_at

    /** Set particular field mass assignable */
    // protected $fillable = ['status'];

    /** Set all the fields mass assignable */
    protected $guarded = [];

    /** Append the custom attribute */
    protected $appends = ['full_name', 'full_address', 'full_position'];

    public function prefix()
    {
        return $this->belongsTo(Prefix::class, 'prefix_id', 'id');
    }

    public function changwat()
    {
        return $this->belongsTo(Changwat::class, 'changwat_id', 'id');
    }

    public function amphur()
    {
        return $this->belongsTo(Amphur::class, 'amphur_id', 'id');
    }

    public function tambon()
    {
        return $this->belongsTo(Tambon::class, 'tambon_id', 'id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id', 'id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function memberOf()
    {
        return $this->hasMany(Section::class, 'employee_id', 'id');
    }

    public function getFullNameAttribute()
    {
        $prefix = $this->prefix?->name ?? '';
        $firstName = $this->firstname ?? '';
        $lastName = $this->lastname ?? '';

        return trim("{$prefix}{$firstName} {$lastName}");
    }

    public function getFullAddressAttribute()
    {
        $addressParts = [];

        if ($this->address) {
            $addressParts[] = $this->address;
        }

        if ($this->tambon?->name) {
            $addressParts[] = "ต. {$this->tambon->name}";
        }

        if ($this->amphur?->name) {
            $addressParts[] = "อ. {$this->amphur->name}";
        }

        if ($this->changwat?->name) {
            $addressParts[] = "จ. {$this->changwat->name}";
        }

        if ($this->zipcode) {
            $addressParts[] = " {$this->zipcode}";
        }

        return implode(' ', $addressParts);
    }

    public function getFullPositionAttribute()
    {
        $position = $this->position?->name ?? '';
        $level = $this->level?->name ?? '';

        return trim("{$position}{$level}");
    }

    protected function avatarUri(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? asset('img/' . $value) : null,
        );
    }

    /**
     * Define the scope using a PHP attribute
     *
     * @param [type] $query
     * @return void
     */
    #[Scope]
    protected function forUserInfo(Builder $query): void
    {
        $query->select(
            'id','firstname','lastname','address','zipcode','avatar_uri','organization_id','user_id',
            'prefix_id','tambon_id','amphur_id','changwat_id','position_id', 'level_id'
        );
    }
}
