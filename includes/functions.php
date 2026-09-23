<?php
require_once '../database/conect.php';

function relatorio($conexao)
{

    $sql = "SELECT * FROM alunos";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->execute();

        $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($alunos as $aluno) {
            echo "ID: {$aluno['id']}<br>";
            echo "nome: {$aluno['nome']}<br>";
            echo "turma: {$aluno['turma']}<br>";
            echo "email: {$aluno['email']}<br>";
            echo "ativo: {$aluno['ativo']}<br>";
            echo "<hr>";
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}

function cadastrar($conexao, $nome, $turma, $ativo, $nasc, $email)
{

    $sql = "INSERT INTO alunos (nome, turma, nascimento, ativo, email) VALUES (:nome, :turma, :nascimento, :ativo, :email)";
    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":turma", $turma);
        $stmt->bindParam("nascimento", $ativo);
        $stmt->bindParam(":ativo", $nasc);
        $stmt->bindParam(":email", $email);

        $stmt->execute();
        echo "Aluno Inserido com Sucesso!";
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}

function apagar($conexao, $id)
{
    $sql = "DELETE FROM alunos WHERE id = :id";
    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        echo "usuario $id removido com sucesso";
    } catch (PDOException $e) {
        echo "erro" . $e->getMessage();
    }
}

function consultar($conexao, $id)
{
    $sql = "SELECT nome, turma, nascimento, ativo FROM alunos WHERE id = :id;";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $aluno = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Aluno: {$aluno['nome']} <br>";
        echo "Turma: {$aluno['turma']}<br>";
        echo "Nascimento: {$aluno['nascimento']}<br>";
        echo "Ativo: {$aluno['ativo']}<br>";
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}

function atualizar($conexao, $id, $nome, $turma, $ativo, $nascimento, $email)
{

    $sql = "UPDATE alunos SET nome = :nome , turma = :turma , nascimento = :nascimento , ativo = :ativo , email = :email WHERE id = :id";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":turma", $turma);
        $stmt->bindParam("nascimento", $ativo);
        $stmt->bindParam(":ativo", $nascimento);
        $stmt->bindParam(":email", $email);

        $stmt->execute();
        echo "Aluno Inserido com Sucesso!";
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}

//Funcões para login:
function cadastra_user($conexao, $email, $senha)
{

    $sql = "INSERT INTO usuarios (email, senha) VALUES (:email, :senha)";
    try {
        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":senha", $senha);

        $stmt->execute();
        echo "Usuario Inserido com Sucesso!";
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
    
}

function consultar_user($conexao, $email)
{
    $sql = "SELECT id, email, senha FROM usuarios WHERE email = :email";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        return $usuario;
        
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
