<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tema
 * 
 * @property int $id_tema
 * @property string $nombre
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection|Referencia[] $referencias
 *
 * @package App\Models
 */
class Tema extends Model
{
	protected $table = 'temas';
	protected $primaryKey = 'id_tema';

	protected $fillable = [
		'nombre'
	];

	public function referencias()
	{
		return $this->belongsToMany(Referencia::class, 'referencia_tema', 'id_tema', 'id_referencia');
	}
}
