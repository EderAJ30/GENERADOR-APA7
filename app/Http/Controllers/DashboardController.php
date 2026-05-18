<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Coleccion;
use App\Models\TiposReferencia;
use App\Models\Materia;
use App\Models\Tema;

class DashboardController extends Controller
{
  public function index(Request $request)
  {
    $search = trim($request->input('search'));

    $query = Coleccion::with([
      'referencia.tipo_referencia',
      'referencia.referencia_autores.autor',
      'referencia.archivos',
      'referencia.materias',
      'referencia.temas'
    ])->where('id_usuario', Auth::id());

    $query->when($search, function ($q) use ($search) {
      $q->whereHas('referencia', function ($qRef) use ($search) {
        $qRef->where(function ($subQuery) use ($search) {
          $subQuery->where('titulo', 'LIKE', "%{$search}%")
            ->orWhere('doi', 'LIKE', "%{$search}%")
            ->orWhereHas('referencia_autores.autor', function ($qAutor) use ($search) {
              $qAutor->where('nombre', 'LIKE', "%{$search}%")
                ->orWhere('apellidos', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('tipo_referencia', function ($qTipo) use ($search) {
              $qTipo->where('nombre', 'LIKE', "%{$search}%");
            });
        });
      });
    });

    $colecciones = $query->orderBy('fecha_agregado', 'desc')
      ->paginate(12)
      ->appends(['search' => $search]);

    $catalogos = [
      'tipos'    => TiposReferencia::all(),
      'materias' => Materia::orderBy('nombre')->get(),
    ];

    return view('dashboard.index', compact('colecciones', 'catalogos', 'search'));
  }
}
