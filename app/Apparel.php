<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apparel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'image_thumbnail',
        'name',
        'apparel_category_id',
        'sizes',
        'description',
        'tokopedia_url',
        'shopee_url',
        'lazada_url',
        'bukalapak_url',
        'blibli_url',
        'status',
        'created_by',
        'updated_by'
    ];

    public function images() {
        return $this->hasMany(ApparelImage::class, 'apparel_id', 'id');
    }

    public function category() {
        return $this->belongsTo(ApparelCategory::class, 'apparel_category_id');
    }

    public function createdBy() {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
