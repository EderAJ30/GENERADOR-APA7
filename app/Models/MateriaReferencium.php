<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MateriaReferencium
 * 
 * @property int $id_materia
 * @property int $id_referencia
 * @property string $tipo_bibliografia
 * @property Carbon $created_at
 * 
 * @property Materia $materia
 * @property Referencia $referencia
 *
 * @package App\Models
 */
class MateriaReferencium extends Model
{
	protected $table = 'materia_referencia';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_materia' => 'int',
		'id_referencia' => 'int'
	];

	protected $fillable = [
		'tipo_bibliografia'
	];

	public function materia()
	{
		return $this->belongsTo(Materia::class, 'id_materia');
	}

	public function referencia()
	{
		return $this->belongsTo(Referencia::class, 'id_referencia');
	}
}
