<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_cuentas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('codigo', 20); // Ej: 10, 1011, 401711 (PCGE)
            $table->string('denominacion');
            $table->enum('nivel', ['elemento', 'cuenta', 'subcuenta', 'divisionaria', 'subdivisionaria'])->default('divisionaria');
            $table->foreignId('cuenta_padre_id')->nullable()->constrained('plan_cuentas')->nullOnDelete();
            $table->boolean('acepta_movimiento')->default(true); // solo cuentas hoja registran movimientos
            $table->timestamps();

            $table->unique(['empresa_id', 'codigo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_cuentas');
    }
};
