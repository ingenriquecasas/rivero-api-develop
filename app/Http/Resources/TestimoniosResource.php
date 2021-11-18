<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestimoniosResource extends JsonResource
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
            'nombre' => $this->nombre,
            'testimonio' => $this->testimonio,
            'estrellas' => $this->estrellas,
            'imagen' => $this->imagen,
            'date' => $this->fecha
        ];
    }
}
