<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('treinos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alunoId')->constrained('alunos')->cascadeOnDelete();
            $table->foreignId('professorId')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nome');
            $table->string('objetivo')->nullable();
            $table->text('observacoes')->nullable();
            $table->string('status')->default('ativo');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('treinos'); }
};
