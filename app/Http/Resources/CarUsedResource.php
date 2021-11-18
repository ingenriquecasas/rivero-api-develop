<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CarUsedResource extends JsonResource
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
            'vin' => $this->vin,
            'year' => $this->year,
            'sucursal' => $this->sucursal,
            'marca' => $this->marca,
            'nombre' => $this->nombre,
            'color' => $this->color,
            'precio' => $this->precio,
            'trim' => $this->trim,
            'trans' => $this->trans,
            'foto' => $this->foto,
            'picture' => $this->picture,
            'descripcion' => $this->descripcion,
            'km' => $this->km,
            'selling_price' => $this->selling_price,
            'precio_total' => $this->precio_total,
            'gastos' => $this->gastos,
            'mercadolibre' => $this->mercadolibre,
            'marca_id' => $this->marca_id,
            'version_id' => $this->version_id,
            'modelo_id' => $this->modelo_id,
            'fecha' => $this->fecha,
            'fechacarga' => $this->fechacarga,
            'similars' => CarUsedOverviewResource::collection($this->getSimilarCars()),
            'panoramic' => $this->panoramicPictures,
        ];
    }
}