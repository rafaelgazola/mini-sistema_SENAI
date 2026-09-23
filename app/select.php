<?php require_once  '../includes/functions.php'; 
require_once  '../login/verifica_user.php'?>
<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>relatorios</title>
</head>

<body>
    <?php include  '../includes/header.php';?>
    <main>
        <?php relatorio($conexao);?>
    </main>

    <?php
    include  '../includes/footer.php'; ?>
</body>

</html>