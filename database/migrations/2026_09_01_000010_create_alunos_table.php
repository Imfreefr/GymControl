<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuarioId')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nome');
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->date('dataNascimento')->nullable();
            $table->string('objetivo')->nullable();
            $table->string('status')->default('ativo');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('alunos'); }
};
