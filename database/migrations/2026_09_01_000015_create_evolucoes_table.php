<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('evolucoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alunoId')->constrained('alunos')->cascadeOnDelete();
            $table->date('data');
            $table->decimal('peso', 8, 2)->nullable();
            $table->decimal('altura', 8, 2)->nullable();
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('evolucoes'); }
};
