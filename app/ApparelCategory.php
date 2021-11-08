<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApparelCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'status'
    ];
}
