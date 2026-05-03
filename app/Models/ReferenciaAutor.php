<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReferenciaAutor
 * 
 * @property int $id_referencia
 * @property int $id_autor
 * @property int $orden
 * @property Carbon $created_at
 * 
 * @property Autore $autore
 * @property Referencia $referencia
 *
 * @package App\Models
 */
class ReferenciaAutor extends Model
{
	protected $table = 'referencia_autor';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_referencia' => 'int',
		'id_autor' => 'int',
		'orden' => 'int'
	];

	protected $fillable = [
		'orden'
	];

	public function autore()
	{
		return $this->belongsTo(Autore::class, 'id_autor');
	}

	public function referencia()
	{
		return $this->belongsTo(Referencia::class, 'id_referencia');
	}
}
