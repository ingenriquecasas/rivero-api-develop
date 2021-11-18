<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarAccessoryYear extends Model
{
    protected $table = 'accesorios_anios';

    public function accessory()
    {
        return $this->belongsTo(CarAccessory::class, 'accesorio_id', 'num_inventario');
    }
}
