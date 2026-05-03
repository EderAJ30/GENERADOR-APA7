<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReferenciaTema
 * 
 * @property int $id_referencia
 * @property int $id_tema
 * @property Carbon $created_at
 * 
 * @property Referencia $referencia
 * @property Tema $tema
 *
 * @package App\Models
 */
class ReferenciaTema extends Model
{
	protected $table = 'referencia_tema';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_referencia' => 'int',
		'id_tema' => 'int'
	];

	public function referencia()
	{
		return $this->belongsTo(Referencia::class, 'id_referencia');
	}

	public function tema()
	{
		return $this->belongsTo(Tema::class, 'id_tema');
	}
}
