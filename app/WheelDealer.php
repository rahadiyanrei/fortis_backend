<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WheelDealer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'wheel_id',
        'dealer_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function dealers() {
        return $this->belongsTo(Dealer::class,'dealer_id');
    }
}
