<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuotationResource extends JsonResource
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
            'car_id' => $this->car_id,
            'desired_for' => $this->desired_for,
            'use_purpose' => $this->use_purpose,
            'expectation' => $this->expectation,
            'color' => $this->color,
            'sell_car' => $this->sell_car,
            'payment' => [
                'method' => $this->payment_method,
                'entry_percentage' => $this->entry_percentage,
                'entry_payment' => $this->entry_payment,
                'months' => $this->months,
                'monthly_payment' => (float) $this->monthly_payment,
            ],
            'warranty' => [
                'selected' => $this->warranty,
                'monthly_payment' => $this->warranty_monthly,
                'options' => $this->warrantyOptions,
            ],
            'gmf_quote' => $this->quote,
        ];
    }
}
