<?php
namespace App\Models;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
#[Fillable(['name','email','password','perfil'])]
#[Hidden(['password','remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected function casts(): array { return ['email_verified_at'=>'datetime','password'=>'hashed']; }
    public function aluno() { return $this->hasOne(Aluno::class,'usuarioId'); }
    public function treinosComoProfessor() { return $this->hasMany(Treino::class,'professorId'); }
    public function ehAdministrador(): bool { return $this->perfil === 'administrador'; }
    public function ehAluno(): bool { return $this->perfil === 'aluno'; }
}
