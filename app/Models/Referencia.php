<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- 1. IMPORTACIÓN DEL TRAIT

/**
 * Class Referencia
 * * @property int $id_referencia
 * @property int $id_tipo_referencia
 * @property int $id_usuario
 * @property int|null $id_editorial
 * @property string $titulo
 * @property int $anio_publicacion
 * @property Carbon|null $fecha_exacta
 * @property string|null $volumen
 * @property string|null $numero
 * @property string|null $paginas
 * @property string|null $isbn_issn
 * @property string|null $doi
 * @property string|null $url
 * @property string|null $resumen
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 * * @property Editorial|null $editorial
 * @property TiposReferencia $tipos_referencia
 * @property Usuario $usuario
 * @property Collection|Archivo[] $archivos
 * @property Collection|Coleccion[] $coleccion
 * @property Collection|Materia[] $materias
 * @property Collection|ReferenciaAutor[] $referencia_autors
 * @property Collection|Tema[] $temas
 *
 * @package App\Models
 */
class Referencia extends Model
{
  use SoftDeletes;
  use HasFactory; // <-- 2. ACTIVACIÓN DEL TRAIT

  protected $table = 'referencias';
  protected $primaryKey = 'id_referencia';

  protected $casts = [
    'id_tipo_referencia' => 'int',
    'id_usuario' => 'int',
    'id_editorial' => 'int',
    'anio_publicacion' => 'int',
    'fecha_exacta' => 'datetime'
  ];

  protected $fillable = [
    'id_tipo_referencia',
    'id_usuario',
    'id_editorial',
    'titulo',
    'anio_publicacion',
    'fecha_exacta',
    'volumen',
    'numero',
    'paginas',
    'isbn_issn',
    'doi',
    'url',
    'resumen'
  ];

  public function editorial()
  {
    return $this->belongsTo(Editorial::class, 'id_editorial');
  }

  public function tipo_referencia()
  {
    return $this->belongsTo(TiposReferencia::class, 'id_tipo_referencia');
  }

  public function usuario()
  {
    return $this->belongsTo(Usuario::class, 'id_usuario');
  }

  public function archivos()
  {
    return $this->hasMany(Archivo::class, 'id_referencia');
  }

  public function colecciones()
  {
    return $this->hasMany(Coleccion::class, 'id_referencia');
  }

  public function materias()
  {
    return $this->belongsToMany(Materia::class, 'materia_referencia', 'id_referencia', 'id_materia')
      ->withPivot('tipo_bibliografia');
  }

  public function referencia_autores()
  {
    return $this->hasMany(ReferenciaAutor::class, 'id_referencia');
  }

  public function temas()
  {
    return $this->belongsToMany(Tema::class, 'referencia_tema', 'id_referencia', 'id_tema');
  }
}