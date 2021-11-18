<?php

namespace App\Http\Controllers;

use App\Models\CarUsed;
use Illuminate\Http\Request;
use App\Http\Resources\CarYearResource;

class CarUsedYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $makes = CarUsed::groupBy('year')
            ->where('panoramica',1)
            ->paginate();

        return CarYearResource::collection($makes);
    }
}

