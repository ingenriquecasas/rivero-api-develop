<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GmfCarPackage extends Model
{
    protected $table = 'gmf_car_packages';

    protected $fillable = [
        'package',
    ];

    protected $casts = [
        'package' => 'json',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
