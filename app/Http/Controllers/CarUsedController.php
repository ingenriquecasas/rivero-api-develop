<?php

namespace App\Http\Controllers;

use App\Models\CarUsed;
use Illuminate\Http\Request;
use App\Http\Resources\CarUsedResource;
use App\Http\Resources\CarUsedOverviewResource;

class CarUsedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cars = CarUsed::hasPicture()
            ->sortableBy([
                'price' => 'precio',
            ], 'precio:asc')
            ->requestFiltering($request)
            ->paginate($request->get('per_page', 15));

        return CarUsedOverviewResource::collection($cars);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $serie
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, CarUsed $carUsed)
    {
        return CarUsedResource::make($carUsed);
    }

    public function seminuevosmm()
    {
        $cars = CarUsed::all();

        return CarUsedOverviewResource::collection($cars);
    }
}
