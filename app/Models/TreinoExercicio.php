<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class TreinoExercicio extends Model
{
    use HasFactory;
    protected $table='treino_exercicios';
    protected $fillable=['treinoId','exercicioId','series','repeticoes','carga','descansoSegundos','ordem'];
    protected function casts(): array { return ['carga'=>'decimal:2']; }
    public function treino() { return $this->belongsTo(Treino::class,'treinoId'); }
    public function exercicio() { return $this->belongsTo(Exercicio::class,'exercicioId'); }
}
