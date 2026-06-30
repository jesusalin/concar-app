<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('numero', 20); // correlativo del asiento, ej: A-00001
            $table->date('fecha');
            $table->enum('tipo', ['apertura', 'compras', 'ventas', 'honorarios', 'planillas', 'ajuste', 'cierre'])->default('ajuste');
            $table->string('glosa')->nullable();
            $table->decimal('total_debe', 14, 2)->default(0);
            $table->decimal('total_haber', 14, 2)->default(0);
            $table->timestamps();

            $table->unique(['empresa_id', 'numero']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asientos');
    }
};
