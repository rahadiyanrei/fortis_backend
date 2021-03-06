<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'uuid',
        'title',
        'body',
        'image',
        'url_ref',
        'status',
        'created_by',
        'updated_by',
        'created_by',
        'updated_by'
    ];

    public function createdBy() {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
