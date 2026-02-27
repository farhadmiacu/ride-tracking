<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id','rider_id','status'
    ];

    public function driver() {
        return $this->belongsTo(User::class,'driver_id');
    }

    public function rider() {
        return $this->belongsTo(User::class,'rider_id');
    }
}
