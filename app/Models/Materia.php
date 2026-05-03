<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Materia
 * 
 * @property int $id_materia
 * @property int $id_area
 * @property string $nombre
 * @property int $semestre
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Area $area
 * @property Collection|Referencia[] $referencias
 *
 * @package App\Models
 */
class Materia extends Model
{
	protected $table = 'materias';
	protected $primaryKey = 'id_materia';

	protected $casts = [
		'id_area' => 'int',
		'semestre' => 'int'
	];

	protected $fillable = [
		'id_area',
		'nombre',
		'semestre'
	];

	public function area()
	{
		return $this->belongsTo(Area::class, 'id_area');
	}

	public function referencias()
	{
		return $this->belongsToMany(Referencia::class, 'materia_referencia', 'id_materia', 'id_referencia')
					->withPivot('tipo_bibliografia');
	}
}
