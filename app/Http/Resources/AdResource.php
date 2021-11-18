<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
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
            'title' => $this->titulo,
            'image' => $this->image,
            'date' => $this->fecha,
            'path' => $this->adSlug,
            'select' => $this->carros_select,
        ];
    }
}
