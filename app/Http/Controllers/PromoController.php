<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;
use App\Http\Resources\PromoResource;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $ads = Promo::where('modelo','imagen')
            ->where('status', 1)
            ->orderBy('fecha', 'desc')
            ->paginate($request->get('per_page', 15));

        return PromoResource::collection($ads);
    }
}
