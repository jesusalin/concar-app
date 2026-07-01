<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuentas_por_cobrar_pagar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('tercero_id')->constrained('terceros')->cascadeOnDelete();
            $table->foreignId('asiento_id')->nullable()->constrained('asientos')->nullOnDelete();
            $table->enum('tipo', ['cobrar', 'pagar']);            // cobrar = cliente, pagar = proveedor
            $table->string('origen')->nullable();                  // "Factura F001-00001", "Manual", etc.
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento')->nullable();
            $table->decimal('monto_original', 14, 2);
            $table->decimal('monto_pagado', 14, 2)->default(0);
            $table->decimal('saldo_pendiente', 14, 2);            // monto_original - monto_pagado
            $table->enum('estado', ['pendiente', 'parcial', 'cancelado'])->default('pendiente');
            $table->string('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuentas_por_cobrar_pagar');
    }
};
