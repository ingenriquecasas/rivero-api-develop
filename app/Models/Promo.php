<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends BaseModel
{
    protected $table = 'promociones';

    public function getImageAttribute()
    {
        // TODO: Clean images in database
        $image = str_replace('"', '', $this->forma);
        // TODO: Improve this to CF/S3
        return $this->publicUrl("promociones/ofertas/{$image}");
    }
}
