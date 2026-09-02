<?php
namespace Database\Seeders;
use App\Models\Aluno;
use App\Models\Exercicio;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@academia.com',
            'password' => Hash::make('password'),
            'perfil' => 'administrador',
        ]);
        $alunoUser = User::create([
            'name' => 'Aluno Teste',
            'email' => 'aluno@academia.com',
            'password' => Hash::make('password'),
            'perfil' => 'aluno',
        ]);
        $aluno = Aluno::create([
            'usuarioId' => $alunoUser->id,
            'nome' => 'Aluno Teste',
            'email' => 'aluno@academia.com',
            'telefone' => '(11) 99999-0000',
            'objetivo' => 'Hipertrofia',
            'status' => 'ativo',
        ]);
        $exercicios = [
            ['nome'=>'Supino Reto','grupoMuscular'=>'Peito','descricao'=>'Supino reto com barra','series'=>4,'repeticoes'=>10,'carga'=>40,'descansoSegundos'=>90],
            ['nome'=>'Agachamento Livre','grupoMuscular'=>'Pernas','descricao'=>'Agachamento com barra','series'=>4,'repeticoes'=>12,'carga'=>50,'descansoSegundos'=>90],
            ['nome'=>'Remada Curvada','grupoMuscular'=>'Costas','descricao'=>'Remada com barra','series'=>3,'repeticoes'=>12,'carga'=>30,'descansoSegundos'=>60],
            ['nome'=>'Desenvolvimento Ombro','grupoMuscular'=>'Ombros','descricao'=>'Desenvolvimento com halteres','series'=>3,'repeticoes'=>12,'carga'=>15,'descansoSegundos'=>60],
            ['nome'=>'Rosca Direta','grupoMuscular'=>'Biceps','descricao'=>'Rosca com barra','series'=>3,'repeticoes'=>12,'carga'=>15,'descansoSegundos'=>60],
            ['nome'=>'Triceps Corda','grupoMuscular'=>'Triceps','descricao'=>'Triceps na polia com corda','series'=>3,'repeticoes'=>12,'carga'=>20,'descansoSegundos'=>60],
        ];
        foreach ($exercicios as $dados) {
            Exercicio::create(array_merge($dados, ['status'=>'ativo']));
        }
    }
}
