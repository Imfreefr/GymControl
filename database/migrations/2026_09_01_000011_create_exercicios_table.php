<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('exercicios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('grupoMuscular')->nullable();
            $table->text('descricao')->nullable();
            $table->integer('series')->default(3);
            $table->integer('repeticoes')->default(12);
            $table->decimal('carga', 8, 2)->default(0);
            $table->integer('descansoSegundos')->default(60);
            $table->string('status')->default('ativo');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('exercicios'); }
};
