<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarAccessory extends BaseModel
{
    protected $table = 'accesorios';

    public function years()
    {
        return $this->hasMany(CarAccessoryYear::class, 'accesorio_id', 'num_inventario')
            ->groupBy(['accesorio_id','anio']);
    }

    public function getPictureAttribute()
    {
        return $this->publicUrl("accesorios/{$this->num_inventario}.jpg");
    }

    public function getPriceTotalAttribute()
    {
        return round((($this->precio + $this->instalacion) * 1.16), 2);
    }
}
