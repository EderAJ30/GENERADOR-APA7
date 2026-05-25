<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- 1. Importamos el trait

/**
 * Class Autores
 * * @property int $id_autor
 * @property string $nombre
 * @property string $apellidos
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * * @property Collection|ReferenciaAutor[] $referencia_autors
 *
 * @package App\Models
 */
class Autor extends Model
{
  use HasFactory; // <-- 2. Activamos el uso de factories en el modelo

  protected $table = 'autores';
  protected $primaryKey = 'id_autor';

  protected $fillable = [
    'nombre',
    'apellidos'
  ];

  public function referencia_autores()
  {
    return $this->hasMany(ReferenciaAutor::class, 'id_autor');
  }
}