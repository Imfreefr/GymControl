<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Exercicio extends Model
{
    use HasFactory;
    protected $table='exercicios';
    protected $fillable=['nome','grupoMuscular','descricao','series','repeticoes','carga','descansoSegundos','status'];
    protected function casts(): array { return ['carga'=>'decimal:2']; }
    public function treinoExercicios() { return $this->hasMany(TreinoExercicio::class,'exercicioId'); }
}
