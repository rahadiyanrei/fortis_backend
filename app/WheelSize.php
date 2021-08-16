<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class WheelSize extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'wheel_id',
        'diameter',
        'option_width',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
}
