<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fac_servicio;


class Fac_servicioController extends Controller
{
    //
    public function index(Request $request)
    {
    
        $buscar= $request->buscar;
        $criterio= $request->criterio;
        if($buscar==''){
            $fac_servicios= Fac_servicio::select(
                'fac_servicios.id',
                'fac_servicios.nombre',
                'fac_servicios.desc')
                ->orderBy('id', 'desc')->paginate(5);       
        } 
        else{
            $fac_servicios = Fac_servicio::select(
                'fac_servicios.id',
                'fac_servicios.nombre',
                'fac_servicios.desc')
            ->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(5);
             
        }
        return[
            'pagination' => [
            'total'            => $fac_servicios->total(),
            'current_page'     => $fac_servicios->currentPage(),
            'per_page'         => $fac_servicios->perPage(),
            'last_page'        => $fac_servicios->lastPage(),
            'from'             => $fac_servicios->firstItem(),
            'to'               => $fac_servicios->lastItem(),           
            ],
            
            'fac_servicios' =>$fac_servicios
        ];
    }
    public function selectFac_servicio(Request $request){
        //   if (!$request->ajax()) return redirect('/');
        $fac_servicios = Fac_servicio::select('id','nombre','desc')
        ->orderBy('nombre', 'asc')
        ->get();
        return ['fac_servicios' =>  $fac_servicios];
    }
    public function store(Request $request)
    {
        $fac_servicios= new Fac_servicio();
        $fac_servicios->nombre = $request->nombre;
        $fac_servicios->desc = $request->desc;    
        $fac_servicios->save();
    }
    public function update(Request $request)
    {
        
        $fac_servicios = Fac_servicio::findOrFail($request->id);
        $fac_servicios->nombre = $request->nombre;
        $fac_servicios->desc = $request->desc;
        $fac_servicios->save();
    }
    public function destroy(Request $request)
    {
        $fac_servicios = Fac_servicio::findOrFail($request->id);
        $fac_servicios->delete();
    }
    
   

}
