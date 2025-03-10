<?php
/* ========================================================
   Autor: Renata . Tonon
   Contato: @retononcode
   Descrição: #sisnote Sistema de notas e Compromissos
   ======================================================== */
session_start();
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Buscar usuário no banco de dados
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin'] = $user['id']; // Armazena ID do admin na sessão
        header("Location: admin.php");
        exit;
    } else {
        echo "<script>alert('Email ou senha incorretos!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
  
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0a0f29;
            color: #00bfff;
            text-align: center;
        }
        .container {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            background: #1a1f4e;
            border-radius: 8px;
            box-shadow: 0 0 10px #00bfff;
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #00bfff;
            background: transparent;
            color: #00bfff;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #00bfff;
            color: #0a0f29;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login Admin</h1>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Senha" required><br>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
