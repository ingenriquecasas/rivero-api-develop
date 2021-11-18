<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Resources\CarResource;
use App\Http\Resources\CarOverviewResource;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cars = Car::sortableBy([
                'price' => 'precio',
                'make' => 'marca',
                'model' => 'modelo',
            ], 'precio:asc')
            ->requestFiltering($request)
            ->groupBy(['modelo', 'ano'])
            ->paginate($request->get('per_page', 15));

        return CarOverviewResource::collection($cars);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $carSlug)
    {
        $car = Car::findFromSlug($carSlug)
            ->orderBy('precio', 'ASC')
            ->first();

        return CarResource::make($car->load([
            'colors',
            'versions',
            'years',
            'details',
        ]));
    }
}
