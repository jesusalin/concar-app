<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpresaUsuario extends Model
{
    protected $table = 'empresa_usuario';

    protected $fillable = ['empresa_id', 'user_id', 'rol'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public const ROLES = ['administrador', 'contador', 'asistente'];
}
