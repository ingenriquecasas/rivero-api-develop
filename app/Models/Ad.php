<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Ad extends BaseModel
{
    protected $table = 'adwords';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'fecha' => 'datetime',
    ];

    public function getImageAttribute()
    {
        // TODO: Clean images in database
        $image = str_replace('"', '', $this->imagen);
        // TODO: Improve this to CF/S3
        return $this->publicUrl("adwords/{$image}");
    }

    public static function generateSlugs()
    {
        $withoutSlug = self::whereNull('slug')->get();

        $withoutSlug->each(function($ad) {
            $ad->generateSlug();
        });
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->titulo);
        $this->save();

        return $this->slug;
    }

    public function getAdSlugAttribute()
    {
        return $this->slug ?? $this->generateSlug();
    }
}
