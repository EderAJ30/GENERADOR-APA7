<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Area
 * 
 * @property int $id_area
 * @property string $nombre
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection|Materia[] $materias
 *
 * @package App\Models
 */
class Area extends Model
{
	protected $table = 'areas';
	protected $primaryKey = 'id_area';

	protected $fillable = [
		'nombre'
	];

	public function materias()
	{
		return $this->hasMany(Materia::class, 'id_area');
	}
}
