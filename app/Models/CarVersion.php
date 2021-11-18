<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;

class CarVersion extends BaseModel
{
    use Compoships;

    protected $table = 'versiones';

    public function car()
    {
        return $this->belongsTo(Car::class, ['modelo', 'ano', 'tipo'], ['modelo', 'ano', 'tipo']);
    }
}
