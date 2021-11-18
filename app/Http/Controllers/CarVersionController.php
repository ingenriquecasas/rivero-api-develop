<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use App\Http\Resources\CarVersionResource;
use App\Models\CarVersion;

class CarVersionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Car $car)
    {
        return CarVersionResource::collection($car->versions);
    }
}
