<?php

namespace App\Http\Controllers;

use App\Models\QR;
use Illuminate\Http\Request;
use App\Http\Resources\QRResource;

class QRController extends Controller
{
    public function index(Request $request)
    {
        $pdf = QR::orderBy('fecha', 'desc')->paginate();

        return QRResource::collection($pdf);
    }

    public function show(Request $request, QR $qr)
    {
        return QRResource::make($qr);
    }
}
