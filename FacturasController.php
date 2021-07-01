<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facturas;

class FacturasController extends Controller
{
    //
        public function getDataFac(Request $request)
    {
        $facturas = Facturas::select('num_fact','valor','concepto','edo','niu','is_pago')
            ->where('niu',\Auth::user()->usuario)
            ->where('edo','1')
            ->get();
        return [
            'factura' => $facturas
        ];
    }
}
