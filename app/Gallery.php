<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'image_thumbnail',
        'title',
        'dashboard_flag',
        'vehicle_brand_id',
        'wheel_id',
        'type',
        'status',
        'created_by',
        'updated_by'
    ];

    public function img_gallery() {
        return $this->hasMany(GalleryImage::class,'gallery_id','id');
    }

    public function wheel() {
        return $this->belongsTo(Wheel::class,'wheel_id');
    }

    public function vehicle_brand() {
        return $this->belongsTo(VehicleBrand::class,'vehicle_brand_id');
    }

    public function createdBy() {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updatedBy() {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
}
