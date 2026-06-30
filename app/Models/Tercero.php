<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tercero extends Model
{
    protected $fillable = ['empresa_id', 'tipo_documento', 'numero_documento', 'razon_social', 'tipo'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
