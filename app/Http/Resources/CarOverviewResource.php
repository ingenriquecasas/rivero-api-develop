<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CarOverviewResource extends JsonResource
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
        ];
    }
}
