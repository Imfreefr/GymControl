<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Treino extends Model
{
    use HasFactory;
    protected $table='treinos';
    protected $fillable=['alunoId','professorId','nome','objetivo','observacoes','status'];
    public function aluno() { return $this->belongsTo(Aluno::class,'alunoId'); }
    public function professor() { return $this->belongsTo(User::class,'professorId'); }
    public function treinoExercicios() { return $this->hasMany(TreinoExercicio::class,'treinoId')->orderBy('ordem'); }
    public function exercicios() { return $this->belongsToMany(Exercicio::class,'treino_exercicios','treinoId','exercicioId')->withPivot(['series','repeticoes','carga','descansoSegundos','ordem'])->withTimestamps(); }
}
