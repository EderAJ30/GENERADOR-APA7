<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class Usuario
 * * @property int $id_usuario
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
 * * @property Rol|null $role
 * @property Collection|Coleccion[] $colecciones
 * @property Collection|Referencia[] $referencias
 *
 * @package App\Models
 */
class Usuario extends Authenticatable
{
  use Notifiable;

  protected $table = 'usuarios';
  protected $primaryKey = 'id_usuario';

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

  protected function casts(): array
  {
    return [
      'estatus'  => 'bool',
      'rol_id'   => 'int',
      'password' => 'hashed',
    ];
  }

  public function role()
  {
    return $this->belongsTo(Rol::class, 'rol_id');
  }

  public function colecciones()
  {
    return $this->hasMany(Coleccion::class, 'id_usuario');
  }

  public function referencias()
  {
    return $this->hasMany(Referencia::class, 'id_usuario');
  }
}
