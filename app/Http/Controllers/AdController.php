<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Ad;
use Illuminate\Http\Request;
use App\Http\Resources\AdResource;

class AdController extends Controller
{
    public function index(Request $request)
    {
        Ad::generateSlugs();

        $ads = Ad::orderBy('fecha', 'desc')->paginate();

        return AdResource::collection($ads);
    }

    public function show(Request $request, $slug)
    {
        Ad::generateSlugs();

        $post = Ad::where('slug', $slug)->first();

        return AdResource::make($post);
    }
}
