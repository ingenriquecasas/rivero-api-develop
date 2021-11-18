<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Http\Requests\QuotationRequest;
use App\Http\Resources\QuotationResource;

class QuotationController extends Controller
{
    public function store(QuotationRequest $request)
    {
        $data = $request->toArray();

        $quotation = Quotation::create($data);

        return QuotationResource::make($quotation);
    }

    public function update(QuotationRequest $request, Quotation $quotation)
    {
        $quotation->update($request->toArray());

        return QuotationResource::make($quotation);
    }
}
