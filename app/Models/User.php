<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function empresas()
    {
        return $this->belongsToMany(Empresa::class, 'empresa_usuario')
            ->withPivot('rol')
            ->withTimestamps();
    }

    public function esAdministradorDe(Empresa $empresa): bool
    {
        return $empresa->rolDe($this->id) === 'administrador';
    }

    public function puedeEditarEn(Empresa $empresa): bool
    {
        return in_array($empresa->rolDe($this->id), ['administrador', 'contador']);
    }
}
