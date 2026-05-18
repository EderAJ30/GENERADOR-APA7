<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Archivo;
use App\Models\Coleccion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ArchivoController extends Controller
{
  public function descargar($id_archivo)
  {
    $archivo = Archivo::with('referencia')->findOrFail($id_archivo);

    $esCreador = $archivo->referencia->id_usuario === Auth::id();

    $estaEnColeccion = Coleccion::where('id_usuario', Auth::id())
      ->where('id_referencia', $archivo->id_referencia)
      ->exists();

    if (!$esCreador && !$estaEnColeccion) {
      abort(403, 'No tienes permisos para visualizar este documento.');
    }

    if (!Storage::disk('local')->exists($archivo->ruta_storage)) {
      abort(404, 'El archivo físico no existe en el servidor.');
    }

    return Storage::disk('local')->response($archivo->ruta_storage, $archivo->nombre_archivo, [
      'Content-Type' => 'application/pdf',
      'Content-Disposition' => 'inline; filename="' . $archivo->nombre_archivo . '"'
    ]);
  }
}
