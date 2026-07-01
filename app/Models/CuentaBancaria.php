<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaBancaria extends Model
{
    protected $table = 'cuentas_bancarias';

    protected $fillable = [
        'empresa_id', 'tipo', 'nombre', 'banco', 'numero_cuenta',
        'moneda', 'saldo_inicial', 'saldo_actual', 'activa', 'plan_cuenta_id',
    ];

    protected $casts = [
        'activa'        => 'boolean',
        'saldo_inicial' => 'decimal:2',
        'saldo_actual'  => 'decimal:2',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoCaja::class);
    }

    public function planCuenta()
    {
        return $this->belongsTo(PlanCuenta::class);
    }

    public function recalcularSaldo(): void
    {
        $ingresos = $this->movimientos()->where('tipo', 'ingreso')->sum('monto');
        $egresos  = $this->movimientos()->where('tipo', 'egreso')->sum('monto');
        $this->saldo_actual = $this->saldo_inicial + $ingresos - $egresos;
        $this->save();
    }
}
