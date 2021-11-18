<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CarAccessoryResource extends JsonResource
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
            'sku' => $this->num_inventario,
            'description' => $this->descripcion,
            'car_model' => $this->auto,
            'setup_time' => $this->tiempo_instalacion,
            'setup_price' => $this->instalacion,
            'price' => $this->precio,
            'price_total' => $this->priceTotal,
            'status' => $this->status,
            'category' => $this->categoria,
            'picture' => $this->picture,
            'years_available' => $this->years()->pluck('anio'),
        ];
    }
}
