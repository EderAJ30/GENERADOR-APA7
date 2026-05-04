<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TiposReferencium
 * 
 * @property int $id_tipo_referencia
 * @property string $nombre
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection|Referencia[] $referencias
 *
 * @package App\Models
 */
class TiposReferencia extends Model
{
  protected $table = 'tipos_referencia';
  protected $primaryKey = 'id_tipo_referencia';

  protected $fillable = [
    'nombre'
  ];

  public function referencias()
  {
    return $this->hasMany(Referencia::class, 'id_tipo_referencia');
  }
}
