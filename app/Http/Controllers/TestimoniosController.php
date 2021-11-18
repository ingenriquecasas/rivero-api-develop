<?php

namespace App\Http\Controllers;

use App\Models\Testimonios;
use Illuminate\Http\Request;
use App\Http\Resources\TestimoniosResource;

class TestimoniosController extends Controller
{
    public function index(Request $request)
    {
        $ads = Testimonios::orderBy('fecha', 'desc')->paginate($request->get('per_page', 4));

        return TestimoniosResource::collection($ads);
    }
}
