<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libro;

class LibroController extends Controller
{
    public function index(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');

            $libros = Libro::paginate(9999999999999999999999);
        
        return [
            'libros' => $libros
        ];
    }

    public function store(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $libros = new Libro();
        $libros->nombre = $request->nombre;
        $libros->codigo = $request->codigo;
        $libros->n_pag = $request->n_pag;
        $libros->edicion = $request->edicion;
        $libros->save();
    }

    public function update(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $libros = Libro::findOrFail($request->id);
        $libros->nombre = $request->nombre;
        $libros->codigo = $request->codigo;
        $libros->n_pag = $request->n_pag;
        $libros->edicion = $request->edicion;
        $libros->save();
    }


    public function destroy(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $libros= Libro::findOrfail($request->id);
        $libros->delete();
    }
}
