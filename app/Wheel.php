<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Wheel extends Model
{
    // Uuid::uuid4()->toString();
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'image',
        'is_new_release',
        'is_discontinued',
        'brand',
        'about',
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
}
