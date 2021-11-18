<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CarUsedOverviewResource extends JsonResource
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
            'id' => $this->serie,
            'marca' => $this->marca,
            'nombre' => $this->nombre,
            'year' => $this->year,
            'color' => $this->color,
            'precio' => $this->precio,
            'foto' => $this->foto,
            'picture' => $this->picture,
            'trans' => $this->trans,
            'selling_price' => $this->selling_price,
            'precio_total' => $this->precio_total,
            'panoramica' => $this->panoramica,
            'mercadolibre' => $this->mercadolibre,
        ];
    }
}
