<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('terceros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->enum('tipo_documento', ['RUC', 'DNI', 'CE', 'PASAPORTE'])->default('RUC');
            $table->string('numero_documento', 15);
            $table->string('razon_social');
            $table->enum('tipo', ['cliente', 'proveedor', 'ambos'])->default('ambos');
            $table->timestamps();

            $table->unique(['empresa_id', 'numero_documento']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terceros');
    }
};
