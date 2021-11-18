<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CarVersionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $version = $this->modelVersion;

        return [
            'id' => $this->id,
            'name' => $version->version,
            'price' => $version->car->precio,
        ];
    }
}
