<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Referencia;
use App\Models\Autor;
use App\Models\Materia;
use App\Models\Tema;
use App\Models\Editorial;
use App\Models\TiposReferencia;
use App\Models\ReferenciaAutor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Coleccion;
use App\Models\Archivo;

class ReferenciaController extends Controller
{
  public function index(Request $request): View
  {
    $search = trim($request->input('search'));

    $query = Referencia::with([
      'tipo_referencia',
      'editorial.pais',
      'referencia_autores.autor',
      'materias',
      'temas'
    ])->latest('created_at');

    $query->when($search, function ($q) use ($search) {
      $q->where(function ($subQuery) use ($search) {
        $subQuery->where('titulo', 'LIKE', "%{$search}%")
          ->orWhere('doi', 'LIKE', "%{$search}%")
          ->orWhereHas('referencia_autores.autor', function ($qAutor) use ($search) {
            $qAutor->where('nombre', 'LIKE', "%{$search}%")
              ->orWhere('apellidos', 'LIKE', "%{$search}%");
          })
          ->orWhereHas('tipo_referencia', function ($qTipo) use ($search) {
            $qTipo->where('nombre', 'LIKE', "%{$search}%");
          })
          ->orWhereHas('materias', function ($qMateria) use ($search) {
            $qMateria->where('nombre', 'LIKE', "%{$search}%");
          });
      });
    });

    $referencias = $query->paginate(8)->appends(['search' => $search]);

    // ✅ MODIFICACIÓN AQUÍ: Filtramos duplicados usando unique() en las colecciones
    $catalogos = [
      'tipos'       => TiposReferencia::all(),
      'autores'     => Autor::orderBy('apellidos')->get()->unique(function ($autor) {
                         return trim($autor->nombre . ' ' . $autor->apellidos);
                       }),
      'editoriales' => Editorial::orderBy('nombre')->get()->unique('nombre'),
      'materias'    => Materia::orderBy('nombre')->get()->unique('nombre'),
      'temas'       => Tema::orderBy('nombre')->get()->unique('nombre'),
    ];

    return view('referencias.index', compact('referencias', 'catalogos', 'search'));
  }

  public function store(Request $request): RedirectResponse
  {
    $validated = $request->validate([
      'titulo'              => 'required|string|max:255',
      'id_tipo_referencia'  => 'required|integer|exists:tipos_referencia,id_tipo_referencia',
      'anio_publicacion'    => 'required|integer|min:1500|max:' . (date('Y') + 1),
      'fecha_exacta'        => 'nullable|date',
      'volumen'             => 'nullable|string|max:20',
      'numero'              => 'nullable|string|max:20',
      'paginas'             => 'nullable|string|max:50',
      'editorial'           => 'nullable|string|max:150',
      'autores_text'        => 'required|string',
      'temas_text'          => 'nullable|string',
      'isbn_issn'           => 'nullable|string|max:20',
      'doi'                 => 'nullable|string|max:100|unique:referencias,doi',
      'url'                 => 'nullable|url|max:500',
      'resumen'             => 'nullable|string',
      'materias'            => 'required|array|min:1',
      'materias.*'          => 'integer|exists:materias,id_materia',
      'archivo'             => 'nullable|file|mimes:pdf|max:20480',
      'comentario_personal' => 'nullable|string|max:1000',
    ]);

    $validated['id_usuario'] = Auth::id();

    DB::beginTransaction();
    try {
      $id_editorial = null;
      if (!empty($request->editorial)) {
        $editorialModel = Editorial::firstOrCreate(['nombre' => trim($request->editorial)]);
        $id_editorial = $editorialModel->id_editorial;
      }

      $referencia = Referencia::create([
        'titulo'             => $validated['titulo'],
        'id_tipo_referencia' => $validated['id_tipo_referencia'],
        'anio_publicacion'   => $validated['anio_publicacion'],
        'fecha_exacta'       => $validated['fecha_exacta'] ?? null,
        'id_editorial'       => $id_editorial,
        'volumen'            => $validated['volumen'] ?? null,
        'numero'             => $validated['numero'] ?? null,
        'paginas'            => $validated['paginas'] ?? null,
        'isbn_issn'          => $validated['isbn_issn'] ?? null,
        'doi'                => $validated['doi'] ?? null,
        'url'                => $validated['url'] ?? null,
        'resumen'            => $validated['resumen'] ?? null,
        'id_usuario'         => $validated['id_usuario'],
      ]);

      $autoresArray = array_filter(array_map('trim', explode(',', $request->autores_text)));
      $autoresVinculados = [];
      $ordenAPA = 1;

      foreach ($autoresArray as $autorNombreCompleto) {
        $partes = explode(' ', $autorNombreCompleto, 2);
        $apellidos = $partes[0];
        $nombre = $partes[1] ?? '';

        $autor = Autor::firstOrCreate([
          'apellidos' => $apellidos,
          'nombre'    => $nombre
        ]);

        if (!in_array($autor->id_autor, $autoresVinculados)) {
          ReferenciaAutor::create([
            'id_referencia' => $referencia->id_referencia,
            'id_autor'      => $autor->id_autor,
            'orden'         => $ordenAPA,
          ]);

          $autoresVinculados[] = $autor->id_autor;
          $ordenAPA++;
        }
      }

      if (!empty($request->temas_text)) {
        $temasArray = array_filter(array_map('trim', explode(',', $request->temas_text)));
        $temasIds = [];
        foreach ($temasArray as $temaStr) {
          $tema = Tema::firstOrCreate(['nombre' => $temaStr]);
          $temasIds[] = $tema->id_tema;
        }
        $referencia->temas()->sync($temasIds);
      }

      $referencia->materias()->syncWithPivotValues($request->materias, ['tipo_bibliografia' => 'basica']);

      Coleccion::create([
        'id_usuario'          => $validated['id_usuario'],
        'id_referencia'       => $referencia->id_referencia,
        'comentario_personal' => $validated['comentario_personal'] ?? null,
      ]);

      if ($request->hasFile('archivo') && $request->file('archivo')->isValid()) {
        $file = $request->file('archivo');
        $ruta = $file->store('referencias_archivos', 'local');

        Archivo::create([
          'id_referencia'  => $referencia->id_referencia,
          'nombre_archivo' => $file->getClientOriginalName(),
          'ruta_storage'   => $ruta,
          'formato'        => strtolower($file->getClientOriginalExtension()) ?: 'pdf',
          'tamano_bytes'   => $file->getSize(),
        ]);
      }

      DB::commit();
      return redirect()->route('referencias.index')->with('success', 'Referencia y archivo guardados correctamente en tu colección.');
    } catch (\Exception $e) {
      DB::rollBack();
      if (isset($ruta) && Storage::disk('local')->exists($ruta)) {
        Storage::disk('local')->delete($ruta);
      }
      throw $e;
    }
  }

  public function destroy($id): RedirectResponse
  {
    DB::beginTransaction();

    try {
      $referencia = Referencia::findOrFail($id);

      $referencia->temas()->detach();
      $referencia->materias()->detach();

      ReferenciaAutor::where('id_referencia', $id)->delete();

      foreach ($referencia->archivos as $archivo) {
        if (!empty($archivo->ruta_storage)) {
          if (Storage::disk('local')->exists($archivo->ruta_storage)) {
            Storage::disk('local')->delete($archivo->ruta_storage);
          }
        }
        $archivo->delete();
      }

      $referencia->delete();

      DB::commit();

      return redirect()->route('referencias.index')
        ->with('success', 'La referencia y sus datos asociados han sido eliminados correctamente.');
    } catch (\Exception $e) {
      DB::rollBack();

      Log::error('Error al eliminar referencia: ' . $e->getMessage());

      return redirect()->route('referencias.index')
        ->withErrors(['error' => 'Ocurrió un problema al intentar eliminar la referencia. Verifica los registros del sistema.']);
    }
  }

  public function update(Request $request, $id): RedirectResponse
  {
    $referencia = Referencia::findOrFail($id);

    if ($referencia->id_usuario !== Auth::id()) {
      abort(403, 'No tienes permisos para modificar esta referencia.');
    }

    $validated = $request->validate([
      'titulo'             => 'required|string|max:255',
      'id_tipo_referencia' => 'required|integer|exists:tipos_referencia,id_tipo_referencia',
      'anio_publicacion'   => 'required|integer|min:1500|max:' . (date('Y') + 1),
      'fecha_exacta'       => 'nullable|date',
      'volumen'            => 'nullable|string|max:20',
      'numero'             => 'nullable|string|max:20',
      'paginas'            => 'nullable|string|max:50',
      'editorial'          => 'nullable|string|max:150',
      'autores_text'       => 'required|string',
      'temas_text'         => 'nullable|string',
      'isbn_issn'          => 'nullable|string|max:20',
      'doi'                => [
        'nullable',
        'string',
        'max:100',
        Rule::unique('referencias', 'doi')->ignore($id, 'id_referencia')
      ],
      'url'                => 'nullable|url|max:500',
      'resumen'            => 'nullable|string',
      'materias'           => 'required|array|min:1',
      'materias.*'         => 'integer|exists:materias,id_materia',
    ]);

    DB::beginTransaction();
    try {
      $id_editorial = null;
      if (!empty($request->editorial)) {
        $editorialModel = Editorial::firstOrCreate(['nombre' => trim($request->editorial)]);
        $id_editorial = $editorialModel->id_editorial;
      }

      $referencia->update([
        'titulo'             => $validated['titulo'],
        'id_tipo_referencia' => $validated['id_tipo_referencia'],
        'anio_publicacion'   => $validated['anio_publicacion'],
        'fecha_exacta'       => $validated['fecha_exacta'] ?? null,
        'id_editorial'       => $id_editorial,
        'volumen'            => $validated['volumen'] ?? null,
        'numero'             => $validated['numero'] ?? null,
        'paginas'            => $validated['paginas'] ?? null,
        'isbn_issn'          => $validated['isbn_issn'] ?? null,
        'doi'                => $validated['doi'] ?? null,
        'url'                => $validated['url'] ?? null,
        'resumen'            => $validated['resumen'] ?? null,
      ]);

      $autoresArray = array_filter(array_map('trim', explode(',', $request->autores_text)));
      $autoresVinculados = [];
      $ordenAPA = 1;

      ReferenciaAutor::where('id_referencia', $id)->delete();

      foreach ($autoresArray as $autorNombreCompleto) {
        $partes = explode(' ', $autorNombreCompleto, 2);
        $apellidos = $partes[0];
        $nombre = $partes[1] ?? '';

        $autor = Autor::firstOrCreate([
          'apellidos' => $apellidos,
          'nombre'    => $nombre
        ]);

        if (!in_array($autor->id_autor, $autoresVinculados)) {
          ReferenciaAutor::create([
            'id_referencia' => $referencia->id_referencia,
            'id_autor'      => $autor->id_autor,
            'orden'         => $ordenAPA,
          ]);

          $autoresVinculados[] = $autor->id_autor;
          $ordenAPA++;
        }
      }

      if (!empty($request->temas_text)) {
        $temasArray = array_filter(array_map('trim', explode(',', $request->temas_text)));
        $temasIds = [];
        foreach ($temasArray as $temaStr) {
          $tema = Tema::firstOrCreate(['nombre' => $temaStr]);
          $temasIds[] = $tema->id_tema;
        }
        $referencia->temas()->sync($temasIds);
      } else {
        $referencia->temas()->sync([]);
      }

      $referencia->materias()->syncWithPivotValues($request->materias, ['tipo_bibliografia' => 'basica']);

      DB::commit();
      return redirect()->route('referencias.index')->with('success', 'Referencia actualizada correctamente.');
    } catch (\Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }
}