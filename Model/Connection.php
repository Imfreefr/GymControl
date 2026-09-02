<?php
namespace Model;
require_once __DIR__ . '/../Config/configuration.php';
use PDO; use PDOException;
class Connection {
    private static ?PDO $instancia=null;
    public static function getInstance(): PDO {
        if(self::$instancia!==null) return self::$instancia;
        $sqlite=__DIR__.'/../database/database.sqlite';
        $usarSqlite=true;
        if(getenv('DB_HOST')||getenv('DB_NAME')) $usarSqlite=false;
        if($usarSqlite){
            if(!file_exists($sqlite)) @touch($sqlite);
            $pdo=new PDO('sqlite:'.$sqlite,null,null,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
            $pdo->exec('PRAGMA foreign_keys=ON');
            self::$instancia=$pdo; self::migrar($pdo); return $pdo;
        }
        try{
            $dsn='mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME.';charset=utf8mb4';
            $pdo=new PDO($dsn,DB_USER,DB_PASSWORD,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
            self::$instancia=$pdo; return $pdo;
        }catch(PDOException $e){ die('Erro conexão: '.e($e->getMessage())); }
    }
    private static function migrar(PDO $pdo): void {
        $q=$pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
        if($q->fetch()) return;
        $schema=file_get_contents(__DIR__.'/../database/schema.sql');
        $pdo->exec($schema);
        $adminHash=password_hash('Admin123!',PASSWORD_ARGON2ID,['memory_cost'=>1<<17,'time_cost'=>4,'threads'=>2]);
        $alunoHash=password_hash('Aluno123!',PASSWORD_ARGON2ID,['memory_cost'=>1<<17,'time_cost'=>4,'threads'=>2]);
        $pdo->prepare("INSERT INTO users (nome,email,senha,tipo) VALUES (?,?,?,?)")->execute(['Admin GymControl','admin@gymcontrol.com',$adminHash,'admin']);
        $pdo->prepare("INSERT INTO users (nome,email,senha,tipo) VALUES (?,?,?,?)")->execute(['Aluno Teste','aluno@teste.com',$alunoHash,'aluno']);
        $alunoId=$pdo->query("SELECT id FROM users WHERE email='aluno@teste.com'")->fetchColumn();
        $pdo->prepare("INSERT INTO alunos (user_id,telefone,data_nascimento,objetivo,status,professor) VALUES (?,?,?,?,?,?)")->execute([$alunoId,'(11) 99999-0000','2000-05-15','Hipertrofia','ativo','Prof. Carlos']);
        $exs=[
            ['Supino reto','Peito','Supino reto com barra',4,'12','60kg','60s'],
            ['Agachamento','Pernas','Agachamento livre',4,'12','80kg','90s'],
            ['Rosca direta','Bíceps','Rosca com barra reta',3,'12','20kg','60s'],
            ['Puxada frontal','Costas','Puxada no pulley',3,'12','50kg','60s'],
            ['Desenvolvimento','Ombros','Desenvolvimento com halteres',3,'12','16kg','60s'],
            ['Tríceps pulley','Tríceps','Tríceps no pulley alto',3,'15','30kg','60s'],
        ];
        $st=$pdo->prepare("INSERT INTO exercicios (nome,grupo_muscular,descricao,series,repeticoes,carga,descanso) VALUES (?,?,?,?,?,?,?)");
        foreach($exs as $ex) $st->execute($ex);
        $pdo->prepare("INSERT INTO treinos (aluno_id,nome,objetivo,observacoes,professor) VALUES (?,?,?,?,?)")->execute([1,'TREINO A - PEITO E TRÍCEPS','Hipertrofia','Foco em peito e tríceps','Prof. Carlos']);
        $treinoId=$pdo->lastInsertId();
        $pdo->prepare("INSERT INTO treino_exercicios (treino_id,exercicio_id,series,repeticoes,carga,descanso) VALUES (?,?,?,?,?,?)")->execute([$treinoId,1,4,'12','60kg','60s']);
        $pdo->prepare("INSERT INTO treino_exercicios (treino_id,exercicio_id,series,repeticoes,carga,descanso) VALUES (?,?,?,?,?,?)")->execute([$treinoId,6,3,'15','30kg','60s']);
        $pdo->prepare("INSERT INTO frequencias (aluno_id,data,presente) VALUES (?,?,?)")->execute([1,date('Y-m-d',strtotime('-2 days')),1]);
        $pdo->prepare("INSERT INTO frequencias (aluno_id,data,presente) VALUES (?,?,?)")->execute([1,date('Y-m-d',strtotime('-1 day')),1]);
        $pdo->prepare("INSERT INTO frequencias (aluno_id,data,presente) VALUES (?,?,?)")->execute([1,date('Y-m-d'),1]);
        $pdo->prepare("INSERT INTO evolucoes (aluno_id,peso,altura,observacao,data) VALUES (?,?,?,?,?)")->execute([1,78.5,1.75,'Início',date('Y-m-d',strtotime('-30 days'))]);
        $pdo->prepare("INSERT INTO evolucoes (aluno_id,peso,altura,observacao,data) VALUES (?,?,?,?,?)")->execute([1,77.2,1.75,'Após 30 dias',date('Y-m-d')]);
    }
}
