<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wheel extends Model
{
    // Uuid::uuid4()->toString();
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'image',
        'is_new_release',
        'is_discontinued',
        'brand',
        'about',
        'PCD',
        'ET',
        'hub',
        'type',
        'status',
        'created_by',
        'updated_by',
        'created_by',
        'updated_by'
    ];

    public function colors() {
        return $this->hasMany(WheelColor::class, 'wheel_id', 'id');
    }

    public function sizes() {
        return $this->hasMany(WheelSize::class, 'wheel_id', 'id');
    }

    public function createdBy() {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updatedBy() {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function dealer() {
        return $this->hasMany(WheelDealer::class, 'wheel_id', 'id');
    }
}
