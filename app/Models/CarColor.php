<?php

namespace App\Models;

use Illuminate\Support\Str;
use Awobaz\Compoships\Compoships;

class CarColor extends BaseModel
{
    use Compoships;

    protected $table = 'inventario_colores';

    public function car()
    {
        return $this->belongsTo(Car::class, ['modelo', 'ano'], ['modelo', 'ano']);
    }

    public function hexadecimal()
    {
        return $this->hasOne(CarColorHex::class, 'nombre', 'color');
    }

    public function getCarImage()
    {
        $color = strtoupper(Str::slug($this->color));

        return $this->publicUrl("{$this->car->modelFolder}/colores/{$color}.png");
    }

    public function getColorHexAttribute()
    {
        return strtoupper(optional($this->hexadecimal)->color);
    }
}
