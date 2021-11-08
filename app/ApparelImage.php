<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApparelImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'apparel_id',
        'image',
        'created_by',
        'updated_by'
    ];
}
