<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Evolucao extends Model
{
    use HasFactory;
    protected $table='evolucoes';
    protected $fillable=['alunoId','data','peso','altura','observacao'];
    protected function casts(): array { return ['data'=>'date','peso'=>'decimal:2','altura'=>'decimal:2']; }
    public function aluno() { return $this->belongsTo(Aluno::class,'alunoId'); }
}
