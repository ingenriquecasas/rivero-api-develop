<?php

namespace App\Models;

use Illuminate\Support\Str;
use Awobaz\Compoships\Compoships;

class CarColorHex extends BaseModel
{
    use Compoships;

    protected $table = 'colores_hex_inventario';

    public function car()
    {
        return $this->belongsTo(CarColor::class, ['modelo', 'ano'], ['modelo', 'ano']);
    }
}
