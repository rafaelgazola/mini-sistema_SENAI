<?php require_once '../includes/functions.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastre-se</title>
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <h1>Faça login para continuar</h1>
        <form action="" method="post">
            <label for="email">E-mail: </label>
            <input type="text" name="email" id="email"><br>
            <label for="senha">Senha: </label>
            <input type="password" name="senha" id="senha"><br>
            <input type="submit" value="Entrar">
            <input type="reset" value="Limpar">
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $usuario = consultar_user($conexao, $_POST['email']);
            if ($usuario && $usuario['email'] == $_POST['email'] && $usuario['senha'] == $_POST['senha']) {
                session_start();
                $_SESSION['id'] = $usuario['id'];
                header("Location: ../index.php");
                exit();
            } else {
                echo "Usuário ou senha inválidos.";
            }
        }
        ?>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>

</html>
