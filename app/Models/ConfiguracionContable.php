<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionContable extends Model
{
    protected $fillable = [
        'empresa_id', 'cuenta_clientes_id', 'cuenta_proveedores_id',
        'cuenta_igv_compras_id', 'cuenta_igv_ventas_id',
        'cuenta_compras_id', 'cuenta_ventas_id',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function cuentaClientes() { return $this->belongsTo(PlanCuenta::class, 'cuenta_clientes_id'); }
    public function cuentaProveedores() { return $this->belongsTo(PlanCuenta::class, 'cuenta_proveedores_id'); }
    public function cuentaIgvCompras() { return $this->belongsTo(PlanCuenta::class, 'cuenta_igv_compras_id'); }
    public function cuentaIgvVentas() { return $this->belongsTo(PlanCuenta::class, 'cuenta_igv_ventas_id'); }
    public function cuentaCompras() { return $this->belongsTo(PlanCuenta::class, 'cuenta_compras_id'); }
    public function cuentaVentas() { return $this->belongsTo(PlanCuenta::class, 'cuenta_ventas_id'); }

    public function estaCompleta(): bool
    {
        return $this->cuenta_clientes_id && $this->cuenta_proveedores_id
            && $this->cuenta_igv_compras_id && $this->cuenta_igv_ventas_id
            && $this->cuenta_compras_id && $this->cuenta_ventas_id;
    }
}
