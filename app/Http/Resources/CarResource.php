<?php

namespace App\Http\Resources;

use App\Http\Resources\CarAccessoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'make' => $this->marca,
            'model' => $this->modelo,
            'year' => $this->ano,
            'description' => $this->descripcion,
            'price' => $this->precio,
            'image' => $this->image,
            'slug' => $this->slug,
            'type' => $this->type->slug,
            'colors' => CarColorResource::collection($this->whenLoaded('colors')),
            'versions' => CarVersionResource::collection($this->whenLoaded('versions')),
            'years' => CarOverviewResource::collection($this->whenLoaded('years')),
            'details' => CarDetailResource::collection($this->whenLoaded('details')),
            'gallery' => $this->gallery,
            'videos' => $this->videos,
            'technology' => $this->technology,
            'similars' => CarOverviewResource::collection($this->getSimilarCars()),
            'accesories' => CarAccessoryResource::collection($this->accesories),
        ];
    }
}
