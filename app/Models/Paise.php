<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Paise
 * 
 * @property int $id_pais
 * @property string $nombre
 * @property string|null $codigo_iso
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection|Editoriale[] $editoriales
 *
 * @package App\Models
 */
class Paise extends Model
{
	protected $table = 'paises';
	protected $primaryKey = 'id_pais';

	protected $fillable = [
		'nombre',
		'codigo_iso'
	];

	public function editoriales()
	{
		return $this->hasMany(Editoriale::class, 'id_pais');
	}
}
