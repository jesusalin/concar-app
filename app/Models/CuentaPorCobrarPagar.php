<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaPorCobrarPagar extends Model
{
    protected $table = 'cuentas_por_cobrar_pagar';

    protected $fillable = [
        'empresa_id', 'tercero_id', 'asiento_id', 'tipo', 'origen',
        'fecha_emision', 'fecha_vencimiento', 'monto_original',
        'monto_pagado', 'saldo_pendiente', 'estado', 'notas',
    ];

    protected $casts = [
        'fecha_emision'     => 'date',
        'fecha_vencimiento' => 'date',
        'monto_original'    => 'decimal:2',
        'monto_pagado'      => 'decimal:2',
        'saldo_pendiente'   => 'decimal:2',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function tercero()
    {
        return $this->belongsTo(Tercero::class);
    }

    public function asiento()
    {
        return $this->belongsTo(Asiento::class);
    }

    public function registrarPago(float $monto): void
    {
        $this->monto_pagado    += $monto;
        $this->saldo_pendiente  = $this->monto_original - $this->monto_pagado;

        if ($this->saldo_pendiente <= 0) {
            $this->saldo_pendiente = 0;
            $this->estado = 'cancelado';
        } else {
            $this->estado = 'parcial';
        }

        $this->save();
    }

    public function estaVencida(): bool
    {
        return $this->fecha_vencimiento
            && $this->fecha_vencimiento->isPast()
            && $this->estado !== 'cancelado';
    }
}
