<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuotationRequest extends FormRequest
{
    protected static $boolValues = [
        'sell_car',
    ];
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'car_id' => 'int|exists:catalogo,id',
            'payment_method' => 'string|in:credit,cash',
            'desired_for' => 'string|in:me,other',
            'use_purpose' => 'string|in:personal,commercial',
            'expectation' => 'string',
            'color' => 'int|exists:inventario_colores,id',
            'sell_car' => 'boolean',
            'entry_percentage' => 'numeric',
            'entry_payment' => 'numeric',
            'months' => 'integer',
            'monthly_payment' => 'numeric',
            'warranty_id' => 'integer|between:1,5',
        ];

        switch($this->method()) {
            case 'POST':
                $rules['car_id'] = 'required|int|exists:catalogo,id';
        }

        return $rules;
    }

    public function prepareForValidation()
    {
        // TODO: Add this to config files
        $defaultValues = [
            'entry_percentage' => 30,
            'months' => 48,
            'warranty_id' => 2,
        ];

        $toMerge = $this->toArray();

        switch ($this->method()) {
            case 'POST':
                $merged = array_merge($defaultValues, $toMerge);
                $this->merge($merged);
                break;
        }
    }
}
