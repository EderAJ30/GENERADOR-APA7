<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Coleccione
 * 
 * @property int $id_coleccion
 * @property int $id_usuario
 * @property int $id_referencia
 * @property Carbon $fecha_agregado
 * @property string|null $comentario_personal
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Referencia $referencia
 * @property Usuario $usuario
 *
 * @package App\Models
 */
class Coleccion extends Model
{
  protected $table = 'colecciones';
  protected $primaryKey = 'id_coleccion';

  protected $casts = [
    'id_usuario' => 'int',
    'id_referencia' => 'int',
    'fecha_agregado' => 'datetime'
  ];

  protected $fillable = [
    'id_usuario',
    'id_referencia',
    'fecha_agregado',
    'comentario_personal'
  ];

  public function referencia()
  {
    return $this->belongsTo(Referencia::class, 'id_referencia');
  }

  public function usuario()
  {
    return $this->belongsTo(Usuario::class, 'id_usuario');
  }
}
