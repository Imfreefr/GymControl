<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('treino_exercicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treinoId')->constrained('treinos')->cascadeOnDelete();
            $table->foreignId('exercicioId')->constrained('exercicios')->cascadeOnDelete();
            $table->integer('series');
            $table->integer('repeticoes');
            $table->decimal('carga', 8, 2)->default(0);
            $table->integer('descansoSegundos')->default(60);
            $table->integer('ordem')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('treino_exercicios'); }
};
