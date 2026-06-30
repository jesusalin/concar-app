<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanCuenta extends Model
{
    protected $fillable = [
        'empresa_id', 'codigo', 'denominacion', 'nivel',
        'cuenta_padre_id', 'acepta_movimiento',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function padre()
    {
        return $this->belongsTo(PlanCuenta::class, 'cuenta_padre_id');
    }

    public function hijas()
    {
        return $this->hasMany(PlanCuenta::class, 'cuenta_padre_id');
    }

    public function detalles()
    {
        return $this->hasMany(AsientoDetalle::class);
    }
}
