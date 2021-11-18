<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarrantyOption extends Model
{
    protected $table = 'warranty_options';

    protected $casts = [
        'price' => 'float',
    ];

    public function quotations()
    {
        return $this->belongsToMany(Quotation::class, 'warranty_id');
    }
}
