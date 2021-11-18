<?php

namespace App\Models;

use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Builder;

class CarUsed extends BaseModel
{
    protected $table = 'inventario';

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('price', function (Builder $builder) {
            $builder->where('precio', '>', 10000);
        });
    }

    public function type()
    {
        return $this->belongsTo(CarType::class, 'tipo_vehiculo');
    }

    public function scopeFindFromSerie($query, $serie = null)
    {
        return $query->where('serie', '=', $serie);
    }

    public function getAnoAttribute()
    {
        return $this->year;
    }

    public function getSimilarCars()
    {
        return self::select(DB::raw('*, ABS('.$this->precio.' - precio) as price_diff'))
            ->where('vin','<>', $this->vin)
            ->groupBy(['marca','nombre','year'])
            ->orderBy('price_diff', 'asc')
            ->take(4)
            ->get();
    }

    public function getPictureAttribute()
    {
        return $this->foto
            ? "https://riverorenta.mx/seminuevos/images/principal/inv_{$this->serie}/imagen-preview.png"
            : Arr::first($this->panoramicPictures);
    }

    public function getPanoramicPicturesAttribute()
    {
        // TODO: Improve this to use S3 and
        if (! $this->panoramica) return [];

        $picturesPath = "/home/riverorenta/public_html/seminuevos/images/vista-360/{$this->serie}/" ;
        $total = App::environment() == 'production'
            ? count(glob($picturesPath."/*.jpg", GLOB_BRACE))
            : 14;

        $pictures = [];
        for ($x = 1; $x <= $total; $x++) {
            $pictures[] =  "https://riverorenta.mx/seminuevos/images/vista-360/{$this->serie}/imagen_{$x}.jpg";
        }

        return $pictures;
    }

    public function scopeHasPicture($query)
    {
        return $query->where('panoramica', 1)
            ->orWhere('foto', 1);
    }

    public function scopeByMake($query, $make = null)
    {
        return $query->when($make, function ($query) use ($make) {
            $makes = explode(',', strtoupper($make));

            return $query->whereIn('marca', $makes);
        });
    }

    public function scopeByYear($query, $year = null)
    {
        return $query->when($year, function ($query) use ($year) {
            $years = explode(',', strtoupper($year));

            return $query->whereIn('year', $years);
        });
    }

    public function scopeByType($query, $type = null)
    {
        return $query->when($type, function ($query) use ($type) {
            $types = explode(',', strtoupper($type));

            return $query->whereHas('type', function($query) use ($types) {
                return $query->whereIn('tipo', $types)
                    ->orWhereIn('slug', $types);
            });
        });
    }

    public function scopeRequestFiltering($query, $request)
    {
        return $query->byMake(Arr::get($request, 'filter.make'))
            ->byYear(Arr::get($request, 'filter.year'))
            ->byType(Arr::get($request, 'filter.type'))
            ->search($request->search, [
                'year',
                'sucursal',
                'marca',
                'nombre',
                'color',
            ]);
    }


}
