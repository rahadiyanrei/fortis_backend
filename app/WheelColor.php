<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WheelColor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'wheel_id',
        'image',
        'color_hex',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function image() {
        return $this->hasMany(WheelColorImage::class, 'wheel_color_id', 'id');
    }
}
