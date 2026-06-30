<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registro_ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('tercero_id')->constrained('terceros'); // cliente
            $table->foreignId('asiento_id')->nullable()->constrained('asientos')->nullOnDelete();
            $table->enum('tipo_comprobante', ['Factura', 'Boleta', 'Nota de Credito', 'Nota de Debito'])->default('Factura');
            $table->string('serie', 10);
            $table->string('numero', 15);
            $table->date('fecha_emision');
            $table->date('fecha_registro');
            $table->decimal('base_imponible', 14, 2)->default(0);
            $table->decimal('igv', 14, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            $table->timestamps();

            $table->unique(['empresa_id', 'tipo_comprobante', 'serie', 'numero']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_ventas');
    }
};
