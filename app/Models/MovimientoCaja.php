<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoCaja extends Model
{
    protected $table = 'movimientos_caja';

    protected $fillable = [
        'empresa_id', 'cuenta_bancaria_id', 'asiento_id',
        'fecha', 'tipo', 'concepto', 'referencia', 'monto', 'saldo_resultante',
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2',
        'saldo_resultante' => 'decimal:2',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function cuentaBancaria()
    {
        return $this->belongsTo(CuentaBancaria::class);
    }

    public function asiento()
    {
        return $this->belongsTo(Asiento::class);
    }
}
