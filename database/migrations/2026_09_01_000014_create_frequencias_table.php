<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('frequencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alunoId')->constrained('alunos')->cascadeOnDelete();
            $table->date('data');
            $table->boolean('presente')->default(true);
            $table->timestamps();
            $table->unique(['alunoId','data']);
        });
    }
    public function down(): void { Schema::dropIfExists('frequencias'); }
};
