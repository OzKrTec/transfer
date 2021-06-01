<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fac_det_servicio;
use App\Fac_servicio;


class Fac_det_servicioController extends Controller
{
    //
    public function index(Request $request)
    {
    
        $buscar= $request->buscar;
        $criterio= $request->criterio;
        if ($buscar==''){
            $fac_det_servicio = Fac_det_servicio::join('fac_servicios','fac_det_servicio.id_servicio','=','fac_servicios.id')
            ->select('fac_det_servicio.id',
                     'fac_det_servicio.nombre',
                     'fac_det_servicio.valor',
                     'fac_det_servicio.desc',
                     'fac_det_servicio.edo',
                     'fac_det_servicio.id_oficina',
                     'fac_det_servicio.id_servicio',
                     'fac_servicios.nombre as nomSer')
            // ->where('fac_det_servicio.id_oficina',\Auth::user()->id_oficina)
            ->orderBy('id', 'desc')->paginate(7);
        }
        else{
            $fac_det_servicio = Fac_det_servicio::join('fac_servicios','fac_det_servicio.id_servicio','=','fac_servicios.id')
            ->select('fac_det_servicio.id',
                     'fac_det_servicio.nombre',
                     'fac_det_servicio.valor',
                     'fac_det_servicio.desc',
                     'fac_det_servicio.edo',
                     'fac_det_servicio.id_oficina',
                     'fac_det_servicio.id_servicio',
                     'fac_servicios.nombre as nomSer')
            // ->where('fac_det_servicio.id_oficina',\Auth::user()->id_oficina)
            ->where('fac_det_servicio.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(7);
        }
        return[
            'pagination' => [
            'total'            => $fac_det_servicio->total(),
            'current_page'     => $fac_det_servicio->currentPage(),
            'per_page'         => $fac_det_servicio->perPage(),
            'last_page'        => $fac_det_servicio->lastPage(),
            'from'             => $fac_det_servicio->firstItem(),
            'to'               => $fac_det_servicio->lastItem(),           
            ],
            
            'fac_det_servicio' =>$fac_det_servicio
        ];
    }
    public function store(Request $request)
    {
        $fac_det_servicio= new Fac_det_servicio();
        $fac_det_servicio->nombre = $request->nombre;
        $fac_det_servicio->valor = $request->valor;
        $fac_det_servicio->desc = $request->desc;
        $fac_det_servicio->edo='1';
        $fac_det_servicio->id_servicio = $request->id_servicio;   
        $fac_det_servicio->id_oficina = \Auth::user()->id_oficina;    
        // $fac_det_servicio->edo = $request->edo;
        // $fac_det_servicio->id_oficina = $request->id_oficina;        
        
        $fac_det_servicio->save();
    }
    public function update(Request $request)
    {
        
        $fac_det_servicio= Fac_det_servicio::findOrFail($request->id);
        $fac_det_servicio->nombre = $request->nombre;
        $fac_det_servicio->valor = $request->valor;
        $fac_det_servicio->desc = $request->desc;
        $fac_det_servicio->edo='1';
        // $fac_det_servicio->edo = $request->edo;
        $fac_det_servicio->id_oficina = $request->id_oficina;        
        $fac_det_servicio->id_servicio = $request->id_servicio;
        $fac_det_servicio->save();
    }
    public function destroy(Request $request)
    {
        $fac_det_servicio = Fac_det_servicio::findOrFail($request->id);
        $fac_det_servicio->delete();
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $fac_det_servicio = Fac_det_servicio::findOrFail($request->id);
        $fac_det_servicio->edo = '0';
        $fac_det_servicio->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $fac_det_servicio = Fac_det_servicio::findOrFail($request->id);
        $fac_det_servicio->edo = '1';
        $fac_det_servicio->save();
    }
   
}
