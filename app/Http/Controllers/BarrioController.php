<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Barrio;
use App\Sector;

class BarrioController extends Controller
{
    public function index(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $barrios = Barrio::join('sectores','barrios.id_sector','=','sectores.id')
            ->select('barrios.id',
                     'barrios.nombre',
                     'barrios.edo',
                     'barrios.id_sector',
                     'sectores.nombre as nomSector')
            ->where('sectores.id_oficina',\Auth::user()->id_oficina)
            ->orderBy('id', 'desc')->paginate(7);
        }
        else{
            $barrios = Barrio::join('sectores','barrios.id_sector','=','sectores.id')
            ->select('barrios.id',
                     'barrios.nombre',
                     'barrios.edo',
                     'barrios.id_sector',
                     'sectores.nombre as nomSector')
            ->where('sectores.id_oficina',\Auth::user()->id_oficina)
            ->where('barrios.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(7);
        }
        

        return [
            'pagination' => [
                'total'        => $barrios->total(),
                'current_page' => $barrios->currentPage(),
                'per_page'     => $barrios->perPage(),
                'last_page'    => $barrios->lastPage(),
                'from'         => $barrios->firstItem(),
                'to'           => $barrios->lastItem(),
            ],
            'barrios' => $barrios
        ];
    }

    // public function selectCategoria(Request $request){
    //     if (!$request->ajax()) return redirect('/');
    //     $categorias = Categoria::where('condicion','=','1')
    //     ->select('id','nombre')->orderBy('nombre', 'asc')->get();
    //     return ['categorias' => $categorias];
    // }

    public function store(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $barrios = new Barrio();
        $barrios->nombre = $request->nombre;
        $barrios->id_sector = $request->id_sector;
        $barrios->edo = '1';
        $barrios->save();
    }
  
    public function update(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $barrios = Barrio::findOrFail($request->id);
        $barrios->nombre = $request->nombre;
        $barrios->id_sector = $request->id_sector;
        $barrios->edo = '1';
        $barrios->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $barrios = Barrio::findOrFail($request->id);
        $barrios->edo = '0';
        $barrios->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $barrios = Barrio::findOrFail($request->id);
        $barrios->edo = '1';
        $barrios->save();
    }
}
