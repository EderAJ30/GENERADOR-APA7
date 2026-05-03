<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Usuario
 * 
 * @property int $id_usuario
 * @property string $nombre_usuario
 * @property string $paterno_usuario
 * @property string $materno_usuario
 * @property string $email
 * @property string|null $remember_token
 * @property bool $estatus
 * @property string $password
 * @property int|null $rol_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Role|null $role
 * @property Collection|Coleccione[] $colecciones
 * @property Collection|Referencia[] $referencias
 *
 * @package App\Models
 */
class Usuario extends Model
{
	protected $table = 'usuarios';
	protected $primaryKey = 'id_usuario';

	protected $casts = [
		'estatus' => 'bool',
		'rol_id' => 'int'
	];

	protected $hidden = [
		'remember_token',
		'password'
	];

	protected $fillable = [
		'nombre_usuario',
		'paterno_usuario',
		'materno_usuario',
		'email',
		'remember_token',
		'estatus',
		'password',
		'rol_id'
	];

	public function role()
	{
		return $this->belongsTo(Role::class, 'rol_id');
	}

	public function colecciones()
	{
		return $this->hasMany(Coleccione::class, 'id_usuario');
	}

	public function referencias()
	{
		return $this->hasMany(Referencia::class, 'id_usuario');
	}
}
