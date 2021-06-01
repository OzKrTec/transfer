<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transporte;

class TransporteController extends Controller
{
    //
    public function index(Request $request)
    {
    
        $buscar= $request->buscar;
        $criterio= $request->criterio;
        if($buscar==''){
            $transportes= Transporte::join('tp_veh','transportes.tp_vehiculo','=','tp_veh.id')
            
            ->select('transportes.id',
            'transportes.nombre',
            'transportes.placa',
            'tp_veh.nombre as vehiculo')
            ->orderBy('transportes.nombre', 'asc')->paginate(4);
        } else{
            $transportes = Transporte::join('tp_veh','transportes.tp_vehiculo','=','tp_veh.id')
            ->select('transportes.id',
            'transportes.nombre',
            'transportes.placa',
            'tp_veh.nombre as vehiculo')
            ->where('transportes.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('transportes.nombre', 'asc')->paginate(4);
        } 
        return[
            'pagination' => [
            'total'            => $transportes->total(),
            'current_page'     => $transportes->currentPage(),
            'per_page'         => $transportes->perPage(),
            'last_page'        => $transportes->lastPage(),
            'from'             => $transportes->firstItem(),
            'to'               => $transportes->lastItem(),           
            ],
            
            'transportes' =>$transportes
        ];
    }
    public function store(Request $request)
    {
        $transportes= new Transporte();
        $transportes->nombre = $request->nombre;
        $transportes->placa = $request->placa;
        $transportes->tp_vehiculo = $request->tp_vehiculo;        
        $transportes->edo='1';
        
        $transportes->save();
    }
    public function update(Request $request)
    {
        
        $transportes= Transporte::findOrFail($request->id);
        $transportes->placa = $request->placa;
        $transportes->nombre = $request->nombre;
        $transportes->tp_vehiculo = $request->tp_vehiculo;
        $transportes->edo='1';
        $transportes->save();
    }
    public function destroy(Request $request)
    {
        $transportes = Transporte::findOrFail($request->id);
        $transportes->delete();
    }
}
