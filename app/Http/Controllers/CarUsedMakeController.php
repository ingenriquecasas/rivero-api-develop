<?php

namespace App\Http\Controllers;

use App\Models\CarUsed;
use Illuminate\Http\Request;
use App\Http\Resources\CarMakeResource;

class CarUsedMakeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $makes = CarUsed::groupBy('marca')
            ->where('panoramica',1)
            ->paginate();

        return CarMakeResource::collection($makes);
    }
}

