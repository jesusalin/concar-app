<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion_contables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->unique()->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('cuenta_clientes_id')->nullable()->constrained('plan_cuentas');     // ej: 1212
            $table->foreignId('cuenta_proveedores_id')->nullable()->constrained('plan_cuentas');  // ej: 4212
            $table->foreignId('cuenta_igv_compras_id')->nullable()->constrained('plan_cuentas');  // ej: 40111
            $table->foreignId('cuenta_igv_ventas_id')->nullable()->constrained('plan_cuentas');   // ej: 40111
            $table->foreignId('cuenta_compras_id')->nullable()->constrained('plan_cuentas');      // ej: 60 (mercaderia/servicio)
            $table->foreignId('cuenta_ventas_id')->nullable()->constrained('plan_cuentas');       // ej: 70/704
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion_contables');
    }
};
