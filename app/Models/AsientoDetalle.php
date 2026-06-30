<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsientoDetalle extends Model
{
    protected $fillable = ['asiento_id', 'plan_cuenta_id', 'glosa', 'debe', 'haber'];

    public function asiento()
    {
        return $this->belongsTo(Asiento::class);
    }

    public function planCuenta()
    {
        return $this->belongsTo(PlanCuenta::class);
    }
}
