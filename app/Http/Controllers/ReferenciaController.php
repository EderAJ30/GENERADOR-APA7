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

class ReferenciaController extends Controller
{
  /* 
    SELECT * FROM `referencias` 
    WHERE (
        `titulo` LIKE '%computacion%' 
        OR `doi` LIKE '%computacion%' 
        OR EXISTS (
            SELECT * FROM `referencia_autor` 
            INNER JOIN `autores` ON `referencia_autor`.`id_autor` = `autores`.`id_autor` 
            WHERE `referencias`.`id_referencia` = `referencia_autor`.`id_referencia` 
            AND (
                `autores`.`nombre` LIKE '%computacion%' 
                OR `autores`.`apellidos` LIKE '%computacion%'
            )
        ) 
        OR EXISTS (
            SELECT * FROM `tipos_referencia` 
            WHERE `referencias`.`id_tipo_referencia` = `tipos_referencia`.`id_tipo_referencia` 
            AND `tipos_referencia`.`nombre` LIKE '%computacion%'
        ) 
        OR EXISTS (
            SELECT * FROM `materias` 
            INNER JOIN `materia_referencia` ON `materias`.`id_materia` = `materia_referencia`.`id_materia` 
            WHERE `referencias`.`id_referencia` = `materia_referencia`.`id_referencia` 
            AND `materias`.`nombre` LIKE '%computacion%'
        )
    ) 
    ORDER BY `created_at` DESC 
    LIMIT 8 OFFSET 0;
  */
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

    $catalogos = [
      'tipos'       => TiposReferencia::all(),
      'autores'     => Autor::orderBy('apellidos')->get(),
      'editoriales' => Editorial::orderBy('nombre')->get(),
      'materias'    => Materia::orderBy('nombre')->get(),
      'temas'       => Tema::orderBy('nombre')->get(),
    ];

    return view('referencias.index', compact('referencias', 'catalogos', 'search'));
  }

  /* 
    SELECT 
    r.id_referencia,
    r.titulo,
    r.anio_publicacion,
    tr.nombre AS tipo,
    e.nombre AS editorial,
    GROUP_CONCAT(DISTINCT CONCAT(a.nombre, ' ', a.apellidos) SEPARATOR ', ') AS autores,
    GROUP_CONCAT(DISTINCT t.nombre SEPARATOR ' | ') AS temas
      FROM referencias r
      JOIN tipos_referencia tr ON r.id_tipo_referencia = tr.id_tipo_referencia
      LEFT JOIN editoriales e ON r.id_editorial = e.id_editorial
      LEFT JOIN referencia_autor ra ON r.id_referencia = ra.id_referencia
      LEFT JOIN autores a ON ra.id_autor = a.id_autor
      LEFT JOIN referencia_tema rt ON r.id_referencia = rt.id_referencia
      LEFT JOIN temas t ON rt.id_tema = t.id_tema
      WHERE r.deleted_at IS NULL
      GROUP BY r.id_referencia;
  */

  public function store(Request $request): RedirectResponse
  {
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
      'doi'                => 'nullable|string|max:100|unique:referencias,doi',
      'url'                => 'nullable|url|max:500',
      'resumen'            => 'nullable|string',
      'materias'           => 'required|array|min:1',
      'materias.*'         => 'integer|exists:materias,id_materia',
    ]);

    $validated['id_usuario'] = 1;

    DB::beginTransaction();
    try {
      $id_editorial = null;
      if (!empty($request->editorial)) {
        $editorialModel = Editorial::firstOrCreate(['nombre' => trim($request->editorial)]);
        $id_editorial = $editorialModel->id_editorial;
      }

      $referencia = Referencia::create([
        'titulo' => $validated['titulo'],
        'id_tipo_referencia' => $validated['id_tipo_referencia'],
        'anio_publicacion' => $validated['anio_publicacion'],
        'fecha_exacta' => $validated['fecha_exacta'] ?? null,
        'id_editorial' => $id_editorial,
        'volumen' => $validated['volumen'] ?? null,
        'numero' => $validated['numero'] ?? null,
        'paginas' => $validated['paginas'] ?? null,
        'isbn_issn' => $validated['isbn_issn'] ?? null,
        'doi' => $validated['doi'] ?? null,
        'url' => $validated['url'] ?? null,
        'resumen' => $validated['resumen'] ?? null,
        'id_usuario' => $validated['id_usuario'],
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
          'nombre' => $nombre
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

      DB::commit();
      return redirect()->route('referencias.index')->with('success', 'Referencia guardada correctamente.');
    } catch (\Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }

  /* 
  INSERT INTO referencias (
    id_tipo_referencia, id_usuario, id_editorial, titulo, 
    anio_publicacion, isbn_issn, resumen
    ) VALUES (1, 5, 2, 'Clean Code', 2008, '978-0132350884', 'A handbook of agile software craftsmanship.');

    INSERT INTO referencia_autor (id_referencia, id_autor, orden) 
      VALUES (LAST_INSERT_ID(), 3, 1);
  */

  public function destroy($id): RedirectResponse
  {
    DB::beginTransaction();

    try {
      $referencia = Referencia::findOrFail($id);

      $referencia->temas()->detach();
      $referencia->materias()->detach();

      ReferenciaAutor::where('id_referencia', $id)->delete();

      foreach ($referencia->archivos as $archivo) {
        if (Storage::disk('public')->exists($archivo->ruta)) {
          Storage::disk('public')->delete($archivo->ruta);
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

  /* 
    DELETE FROM referencias WHERE id_referencia = 10;
  */

  public function update(Request $request, $id): RedirectResponse
  {
    $referencia = Referencia::findOrFail($id);

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
      'doi'                => 'nullable|string|max:100|unique:referencias,doi,' . $id . ',id_referencia',
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
        'titulo' => $validated['titulo'],
        'id_tipo_referencia' => $validated['id_tipo_referencia'],
        'anio_publicacion' => $validated['anio_publicacion'],
        'fecha_exacta' => $validated['fecha_exacta'] ?? null,
        'id_editorial' => $id_editorial,
        'volumen' => $validated['volumen'] ?? null,
        'numero' => $validated['numero'] ?? null,
        'paginas' => $validated['paginas'] ?? null,
        'isbn_issn' => $validated['isbn_issn'] ?? null,
        'doi' => $validated['doi'] ?? null,
        'url' => $validated['url'] ?? null,
        'resumen' => $validated['resumen'] ?? null,
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
          'nombre' => $nombre
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

  /* 
    UPDATE referencias 
      SET titulo = 'Clean Code: Revised Edition', 
          anio_publicacion = 2009 
      WHERE id_referencia = 10;
  */
}
