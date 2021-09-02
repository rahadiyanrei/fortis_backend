<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WheelColorImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'wheel_color_id',
        'image',
        'created_by',
        'updated_by'
    ];
}
