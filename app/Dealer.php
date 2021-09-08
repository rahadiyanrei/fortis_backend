<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'long',
        'lat',
        'province_id',
        'name',
        'address',
        'email',
        'phone_number',
        'status',
        'created_by',
        'updated_by',
        'created_by',
        'updated_by'
    ];

    public function province() {
        return $this->hasOne(Province::class, 'id', 'province_id');
    }

    public function wheel_dealer() {
        return $this->hasMany(WheelDealer::class,'dealer_id','id');
    }

    public function createdBy() {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updatedBy() {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
}
