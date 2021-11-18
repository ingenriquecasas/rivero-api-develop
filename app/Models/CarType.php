<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class CarType extends Model
{
    protected $table = 'referencia_tipo_vehiculo';

    public function cars()
    {
        return $this->hasMany(Car::class, 'tipo_vehiculo');
    }
}
