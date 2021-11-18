<?php

namespace App\Http\Controllers;

use App\Http\Resources\CarMakeResource;
use App\Models\Car;
use Illuminate\Http\Request;

class CarMakeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $makes = Car::groupBy('marca')->paginate();

        return CarMakeResource::collection($makes);
    }
}

