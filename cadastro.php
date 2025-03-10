<?php
/* ========================================================
   Autor: Renata . Tonon
   Contato: @retononcode
   Descrição: #sisnote Sistema de notas e Compromissos
   ======================================================== */
require 'db.php';
/* ========================================================
   Autor: Renata . Tonon
   Contato: @retononcode
   Descrição: #sisnote Sistema de notas e Compromissos
   ======================================================== */


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (nome, telefone, email, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nome, $telefone, $email, $password]);

    echo "<p style='color: #00bfff;'>Cadastro realizado com sucesso! <a href='login.php' style='color: #0080ff;'>Login</a></p>";
}
/* ========================================================
   Autor: Renata . Tonon
   Contato: @retononcode
   Descrição: #sisnote Sistema de notas e Compromissos
   ======================================================== */
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0a0f29;
            color: #00bfff;
            text-align: center;
        }
        .container {
            width: 300px;
            margin: 50px auto;
            padding: 20px;
            background: #1a1f4e;
            border-radius: 8px;
            box-shadow: 0 0 10px #00bfff;
        }
.logo {
    width: 150px;
    height: 150px; /* Garante que a altura seja igual à largura */
    border-radius: 50%; /* Faz a imagem ficar redonda */
    overflow: hidden; /* Garante que a imagem não ultrapasse a borda */
    object-fit: cover; /* Mantém o enquadramento correto da imagem */
    margin-bottom: 15px;
}

        h1 {
            color: #00bfff;
            margin-bottom: 10px;
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
        button:hover {
            background: #0080ff;
        }
        a {
            color: #00bfff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo adicionada aqui -->
        <img src="logo.webp" alt="Logo" class="logo">
        <h1>Cadastro</h1>
        <form method="POST">
            <input type="text" name="nome" placeholder="Nome Completo" required><br>
            <input type="text" name="telefone" placeholder="Telefone" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Senha" required><br>
            <button type="submit">Cadastrar</button>
        </form>
        <p>Já tem conta? <a href="painel.php">Faça login</a></p>
    </div>
  
    <!-- Rodapé -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>
