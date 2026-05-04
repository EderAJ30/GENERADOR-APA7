<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Editoriale
 * 
 * @property int $id_editorial
 * @property int|null $id_pais
 * @property string $nombre
 * @property string|null $ciudad_sede
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Pais|null $paise
 * @property Collection|Referencia[] $referencias
 *
 * @package App\Models
 */
class Editorial extends Model
{
  protected $table = 'editoriales';
  protected $primaryKey = 'id_editorial';

  protected $casts = [
    'id_pais' => 'int'
  ];

  protected $fillable = [
    'id_pais',
    'nombre',
    'ciudad_sede'
  ];

  public function pais()
  {
    return $this->belongsTo(Pais::class, 'id_pais');
  }

  public function referencias()
  {
    return $this->hasMany(Referencia::class, 'id_editorial');
  }
}
