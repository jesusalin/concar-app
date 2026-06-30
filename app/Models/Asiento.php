<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asiento extends Model
{
    protected $fillable = [
        'empresa_id', 'numero', 'fecha', 'tipo', 'glosa',
        'total_debe', 'total_haber',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function detalles()
    {
        return $this->hasMany(AsientoDetalle::class);
    }

    // Recalcula y guarda los totales en base a las líneas del detalle
    public function recalcularTotales(): void
    {
        $this->total_debe = $this->detalles()->sum('debe');
        $this->total_haber = $this->detalles()->sum('haber');
        $this->save();
    }

    public function estaCuadrado(): bool
    {
        return round((float) $this->total_debe, 2) === round((float) $this->total_haber, 2);
    }
}
