<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Aluno extends Model
{
    use HasFactory;
    protected $table='alunos';
    protected $fillable=['usuarioId','nome','email','telefone','dataNascimento','objetivo','status','observacoes'];
    protected function casts(): array { return ['dataNascimento'=>'date']; }
    public function usuario() { return $this->belongsTo(User::class,'usuarioId'); }
    public function treinos() { return $this->hasMany(Treino::class,'alunoId'); }
    public function frequencias() { return $this->hasMany(Frequencia::class,'alunoId'); }
    public function evolucoes() { return $this->hasMany(Evolucao::class,'alunoId'); }
}
