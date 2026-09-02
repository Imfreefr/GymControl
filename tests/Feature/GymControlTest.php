<?php
namespace Tests\Feature;
use App\Models\Aluno;
use App\Models\Exercicio;
use App\Models\Treino;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GymControlTest extends TestCase
{
    use RefreshDatabase;

    private function criarAdmin(): User { return User::factory()->administrador()->create(['email'=>'admin@teste.com','password'=>bcrypt('password')]); }
    private function criarAlunoUser(string $email='aluno@teste.com'): User {
        $u = User::factory()->create(['email'=>$email,'password'=>bcrypt('password'),'perfil'=>'aluno']);
        Aluno::create(['usuarioId'=>$u->id,'nome'=>'Aluno '.$email,'email'=>$email,'status'=>'ativo']);
        return $u;
    }

    public function test_inicio_carrega(): void { $this->get('/')->assertStatus(200)->assertSee('GymControl'); }
    public function test_login_pagina(): void { $this->get('/login')->assertStatus(200)->assertSee('Entrar'); }
    public function test_cadastro_pagina(): void { $this->get('/cadastro')->assertStatus(200)->assertSee('Criar conta'); }
    public function test_cadastro_post_cria_usuario_e_aluno(): void {
        $res = $this->post('/cadastro', ['name'=>'Novo','email'=>'novo@teste.com','password'=>'password','password_confirmation'=>'password']);
        $res->assertRedirect('/painel-aluno');
        $this->assertDatabaseHas('users',['email'=>'novo@teste.com','perfil'=>'aluno']);
        $this->assertDatabaseHas('alunos',['email'=>'novo@teste.com']);
    }
    public function test_cadastro_post_falha_validacao(): void {
        $this->post('/cadastro', ['name'=>'','email'=>'invalido','password'=>'123','password_confirmation'=>'456'])->assertSessionHasErrors();
    }
    public function test_login_funciona_admin_redireciona_painel_admin(): void {
        $admin = $this->criarAdmin();
        $this->post('/login', ['email'=>'admin@teste.com','password'=>'password'])->assertRedirect('/painel-admin');
    }
    public function test_login_funciona_aluno_redireciona_painel_aluno(): void {
        $this->criarAlunoUser();
        $this->post('/login', ['email'=>'aluno@teste.com','password'=>'password'])->assertRedirect('/painel-aluno');
    }
    public function test_login_falha_credenciais_invalidas(): void {
        $this->criarAlunoUser();
        $this->post('/login', ['email'=>'aluno@teste.com','password'=>'errada'])->assertSessionHasErrors();
    }
    public function test_listagem_exercicios_get_para_aluno(): void {
        $aluno = $this->criarAlunoUser();
        Exercicio::create(['nome'=>'Supino','grupoMuscular'=>'Peito','status'=>'ativo','series'=>3,'repeticoes'=>10,'carga'=>0,'descansoSegundos'=>60]);
        $this->actingAs($aluno)->get('/exercicios')->assertStatus(200)->assertSee('Supino');
    }
    public function test_busca_exercicios_get_filtra(): void {
        $aluno = $this->criarAlunoUser();
        Exercicio::create(['nome'=>'Supino','grupoMuscular'=>'Peito','status'=>'ativo','series'=>3,'repeticoes'=>10,'carga'=>0,'descansoSegundos'=>60]);
        Exercicio::create(['nome'=>'Agachamento','grupoMuscular'=>'Pernas','status'=>'ativo','series'=>3,'repeticoes'=>10,'carga'=>0,'descansoSegundos'=>60]);
        $this->actingAs($aluno)->get('/exercicios?busca=Supino')->assertStatus(200)->assertSee('Supino')->assertDontSee('Agachamento');
    }
    public function test_aluno_nao_acessa_alunos_403(): void {
        $aluno = $this->criarAlunoUser();
        $this->actingAs($aluno)->get('/alunos')->assertStatus(403);
    }
    public function test_admin_lista_alunos_get(): void {
        $admin = $this->criarAdmin();
        Aluno::create(['nome'=>'Teste Listagem','status'=>'ativo']);
        $this->actingAs($admin)->get('/alunos')->assertStatus(200)->assertSee('Teste Listagem');
    }
    public function test_admin_cadastra_aluno_post(): void {
        $admin = $this->criarAdmin();
        $this->actingAs($admin)->post('/alunos', ['nome'=>'Aluno Novo','status'=>'ativo'])->assertRedirect('/alunos');
        $this->assertDatabaseHas('alunos',['nome'=>'Aluno Novo']);
    }
    public function test_aluno_isolamento_treino_so_ve_proprio(): void {
        $aluno1 = $this->criarAlunoUser('a1@teste.com');
        $aluno2 = $this->criarAlunoUser('a2@teste.com');
        $a1 = Aluno::where('usuarioId',$aluno1->id)->first();
        $a2 = Aluno::where('usuarioId',$aluno2->id)->first();
        $t1 = Treino::create(['alunoId'=>$a1->id,'professorId'=>$aluno1->id,'nome'=>'Treino A1','status'=>'ativo']);
        $t2 = Treino::create(['alunoId'=>$a2->id,'professorId'=>$aluno1->id,'nome'=>'Treino A2','status'=>'ativo']);
        $this->actingAs($aluno1)->get('/treinos')->assertSee('Treino A1')->assertDontSee('Treino A2');
        $this->actingAs($aluno1)->get("/treinos/{$t2->id}")->assertStatus(403);
        $this->actingAs($aluno1)->get("/treinos/{$t1->id}")->assertStatus(200);
    }
    public function test_aluno_isolamento_frequencia(): void {
        $aluno1 = $this->criarAlunoUser('f1@teste.com');
        $aluno2 = $this->criarAlunoUser('f2@teste.com');
        $a1 = Aluno::where('usuarioId',$aluno1->id)->first();
        $a2 = Aluno::where('usuarioId',$aluno2->id)->first();
        \App\Models\Frequencia::create(['alunoId'=>$a1->id,'data'=>'2026-09-01','presente'=>true]);
        \App\Models\Frequencia::create(['alunoId'=>$a2->id,'data'=>'2026-09-01','presente'=>true]);
        $resp = $this->actingAs($aluno1)->get('/frequencias');
        $resp->assertStatus(200);
        $conteudo = $resp->getContent();
        $this->assertStringContainsString($a1->nome, $conteudo);
    }
    public function test_logout_funciona(): void {
        $aluno = $this->criarAlunoUser();
        $this->actingAs($aluno)->post('/logout')->assertRedirect('/');
    }
}
