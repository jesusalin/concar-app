<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroVenta extends Model
{
    protected $fillable = [
        'empresa_id', 'tercero_id', 'asiento_id', 'tipo_comprobante',
        'serie', 'numero', 'fecha_emision', 'fecha_registro',
        'base_imponible', 'igv', 'total',
    ];

    public function empresa() { return $this->belongsTo(Empresa::class); }
    public function tercero() { return $this->belongsTo(Tercero::class); }
    public function asiento() { return $this->belongsTo(Asiento::class); }
}
