<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invitaciones_equipo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained()->onDelete('cascade');
            $table->foreignId('participante_id')->constrained()->onDelete('cascade');
            $table->foreignId('perfil_sugerido_id')->nullable()->constrained('perfiles')->onDelete('set null');
            $table->text('mensaje')->nullable();
            $table->enum('estado', ['pendiente', 'aceptada', 'rechazada'])->default('pendiente');
            $table->foreignId('enviada_por_participante_id')->constrained('participantes')->onDelete('cascade');
            $table->timestamp('respondida_en')->nullable();
            $table->timestamps();
            $table->unique(['equipo_id', 'participante_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitaciones_equipo');
    }
};
