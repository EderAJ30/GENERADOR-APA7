<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Autore
 * 
 * @property int $id_autor
 * @property string $nombre
 * @property string $apellidos
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection|ReferenciaAutor[] $referencia_autors
 *
 * @package App\Models
 */
class Autore extends Model
{
	protected $table = 'autores';
	protected $primaryKey = 'id_autor';

	protected $fillable = [
		'nombre',
		'apellidos'
	];

	public function referencia_autors()
	{
		return $this->hasMany(ReferenciaAutor::class, 'id_autor');
	}
}
