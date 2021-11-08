<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    
    use HasFactory;
    protected $table = 'social_medias';
    protected $fillable = [
        'facebook',
        'linkedin',
        'youtube',
        'twitter',
        'instagram',
        'created_by',
        'updated_by',
        'created_by',
        'updated_by'
    ];
}
