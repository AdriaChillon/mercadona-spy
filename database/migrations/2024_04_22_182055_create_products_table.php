<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique(); // Asegúrate de que el ID externo sea único
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->string('url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('packaging')->nullable(); // Nuevo campo para el empaque
            $table->decimal('unit_price', 8, 2)->nullable(); // Nuevo campo para precio por unidad
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
