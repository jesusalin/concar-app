<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = ['ruc', 'razon_social', 'moneda', 'fecha_inicio_actividades'];

    public function planCuentas()
    {
        return $this->hasMany(PlanCuenta::class);
    }

    public function asientos()
    {
        return $this->hasMany(Asiento::class);
    }

    public function terceros()
    {
        return $this->hasMany(Tercero::class);
    }

    public function registroCompras()
    {
        return $this->hasMany(RegistroCompra::class);
    }

    public function registroVentas()
    {
        return $this->hasMany(RegistroVenta::class);
    }

    public function configuracionContable()
    {
        return $this->hasOne(ConfiguracionContable::class);
    }

    // ── Nuevas relaciones ──────────────────────────────────────────

    public function cuentasBancarias()
    {
        return $this->hasMany(CuentaBancaria::class);
    }

    public function movimientosCaja()
    {
        return $this->hasMany(MovimientoCaja::class);
    }

    public function cuentasPorCobrarPagar()
    {
        return $this->hasMany(CuentaPorCobrarPagar::class);
    }

    // ── Usuarios ───────────────────────────────────────────────────

    public function usuarios()
    {
        return $this->belongsToMany(\App\Models\User::class, 'empresa_usuario')
            ->withPivot('id', 'rol')
            ->withTimestamps();
    }

    public function rolDe($userId): ?string
    {
        $pivote = $this->usuarios()->where('user_id', $userId)->first();
        return $pivote?->pivot->rol;
    }
}
