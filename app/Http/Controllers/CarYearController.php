<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarUsed;
use Illuminate\Http\Request;
use App\Http\Resources\CarYearResource;

class CarYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $makes = Car::groupBy('ano')->paginate();

        return CarYearResource::collection($makes);
    }
}

