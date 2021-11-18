<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends BaseModel
{
    protected $table = 'blogs';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'fecha' => 'datetime',
    ];

  
    public function getImageAttribute()
    {
        // TODO: Migrate this to S3
        return $this->publicUrl("/blog/{$this->id}/portada.png");
    }
}
