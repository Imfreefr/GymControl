<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Frequencia extends Model
{
    use HasFactory;
    protected $table='frequencias';
    protected $fillable=['alunoId','data','presente'];
    protected function casts(): array { return ['data'=>'date','presente'=>'boolean']; }
    public function aluno() { return $this->belongsTo(Aluno::class,'alunoId'); }
}
