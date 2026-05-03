<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Archivo
 * 
 * @property int $id_archivo
 * @property int $id_referencia
 * @property string $nombre_archivo
 * @property string $ruta_storage
 * @property string $formato
 * @property int $tamano_bytes
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Referencia $referencia
 *
 * @package App\Models
 */
class Archivo extends Model
{
	protected $table = 'archivos';
	protected $primaryKey = 'id_archivo';

	protected $casts = [
		'id_referencia' => 'int',
		'tamano_bytes' => 'int'
	];

	protected $fillable = [
		'id_referencia',
		'nombre_archivo',
		'ruta_storage',
		'formato',
		'tamano_bytes'
	];

	public function referencia()
	{
		return $this->belongsTo(Referencia::class, 'id_referencia');
	}
}
