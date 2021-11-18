<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use App\Http\Resources\BlogPostResource;
use App\Http\Resources\BlogPostOverviewResource;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        return BlogPostOverviewResource::collection(
            BlogPost::sortableBy(['fecha'], 'fecha:desc')
                ->paginate($request->get('per_page', 15))
        );
    }

    public function show(Request $request, $year, $month, $day, $slug)
    {
        $date = Carbon::parse($year . '/' . $month . '/' . $day);
        $post = BlogPost::whereDate('fecha', '=', $date->toDateString())->where('slug', $slug)->first();

        return BlogPostResource::make($post);
    }
}
