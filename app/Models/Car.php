<?php

namespace App\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Libraries\GmFinancial;
use Awobaz\Compoships\Compoships;
use Illuminate\Support\Facades\Storage;

class Car extends BaseModel
{
    use Compoships;

    protected $table = 'catalogo';

    public function colors()
    {
        return $this->hasMany(CarColor::class, ['modelo', 'ano'], ['modelo', 'ano']);
    }

    public function versions()
    {
        // TODO: Find the correct way to get the relation
        return $this->hasMany(Car::class, ['modelo', 'ano'], ['modelo', 'ano'])
            ->whereHas('modelVersion');
    }

    public function modelVersion()
    {
        return $this->hasOne(CarVersion::class, ['modelo', 'ano', 'tipo'], ['modelo', 'ano', 'tipo']);
    }

    public function years()
    {
        return $this->hasMany(Car::class, 'modelo', 'modelo')->groupBy('ano');
    }

    public function type()
    {
        return $this->belongsTo(CarType::class, 'tipo_vehiculo');
    }

    public function details()
    {
        return $this->hasMany(CarDetail::class, ['modelo', 'anio'], ['modelo', 'ano']);
    }

    public function technologies()
    {
        return $this->hasMany(CarTechnology::class, ['modelo', 'ano'], ['modelo', 'ano'])
            ->where('tipo', 'tecnologia');
    }

    public function gmfPackage()
    {
        return $this->hasOne(GmfCarPackage::class, 'car_id');
    }

    public function accesories()
    {
        return $this->hasMany(CarAccessory::class, 'auto', 'modelo')
            ->whereHas('years', function($year) {
                return $year->where('anio', $this->ano);
            });
    }

    public function getSimilarCars()
    {
        return self::select(DB::raw('*, ABS('.$this->precio.' - precio) as price_diff'))
            ->mostRecentModelOnly()
            ->where('id','<>', $this->id)
            ->groupBy(['marca','modelo','ano'])
            ->orderBy('price_diff', 'asc')
            ->take(4)
            ->get();
    }

    public function scopeMostRecentModelOnly($query)
    {
        $table = $this->getTable();
        $subQuery = "from {$table} c WHERE c.modelo = {$table}.modelo";

        return $query->where('ano', '>=', Carbon::now()->subYear()->format('Y'))
            ->whereRaw("ano = (select MAX(ano) {$subQuery}) AND precio = (select MIN(precio) {$subQuery} AND c.ano = {$table}.ano)");
    }

    public function scopeFindFromSlug($query, $slug)
    {
        $aux = explode('-', $slug);

        $lastIndex = count($aux) - 1;

        $brand = $aux[0];
        $year = $aux[$lastIndex];

        unset($aux[$lastIndex]);
        unset($aux[0]);

        $model = implode(' ', $aux);

        return $query->where('marca', 'like', $brand)
            ->where('modelo', 'like', $model)
            ->where('ano', 'like', $year);
    }

    public function scopeByMake($query, $make = null)
    {
        return $query->when($make, function ($query) use ($make) {
            $makes = explode(',', strtoupper($make));

            return $query->whereIn('marca', $makes);
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

    public function scopeByYear($query, $year = null)
    {
        return $query->when($year, function ($query) use ($year) {
            $years = explode(',', strtoupper($year));

            // Make a year filtering variation if most_recent filter is used
            return !! request()->get('most_recent', false)
                ? $query->whereHas('years', function($query) use ($years){
                    $query->whereIn('ano', $years);
                })
                : $query->whereIn('ano', $years);
        });
    }

    public function scopeRequestFiltering($query, $request)
    {
        return $query->when($request->filled('most_recent'), function($query) {
                return $query->mostRecentModelOnly();
            })
            ->byMake(Arr::get($request, 'filter.make'))
            ->byYear(Arr::get($request, 'filter.year'))
            ->byType(Arr::get($request, 'filter.type'))
            ->search($request->search, [
                'marca',
                'modelo',
                'ano'
            ]);
    }

    public function getPriceAttribute()
    {
        return $this->precio;
    }

    public function getImageAttribute()
    {
        $color = $this->colors()->skip(1)->first();

        return optional($color)->getCarImage();
    }

    public function getSlugAttribute()
    {
        return Str::slug($this->marca . '-' . $this->modelo . '-' . $this->ano);
    }

    public function getGalleryAttribute()
    {
        $images = collect(Storage::disk('s3')->files("{$this->modelFolder}/galeria"));

        return $images->map(function($image) {
            return $this->publicUrl($image);
        });
    }

    public function getVideosAttribute()
    {
        $videos = collect(Storage::disk('s3')->files("{$this->modelFolder}/videos"));

        return $videos->map(function($video) {
            return $this->publicUrl($video);
        });
    }

    public function getTechnologyAttribute()
    {
        return $this->technologies->map(function($tech) {
            return [
                'id' => $tech->id,
                'name' => $tech->tecnologia,
                'image' => $this->publicUrl('tecnologias/' . Str::slug($tech->tecnologia) . '.mp4'),
            ];
        });
    }

    public function getModelFolderAttribute()
    {
        $brand = $this->marca;
        $model = $this->modelo;
        $year = $this->ano;

        $model = Str::slug(strtolower($model . '-' . $year));
        $brand = strtolower($brand);

        return "autos-landing/{$brand}/{$model}";
    }

    /**
     * Get the BOAKey for the car
     *
     * @param  \App\Libraries\GmFinancial  $gmf
     * @return object
     */
    public function getGmfPackage(GmFinancial $gmf = null): ?object
    {
        return ($gmf ?? new GmFinancial())->getCarPackage($this);
    }
}
