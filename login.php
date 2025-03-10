<?php
/* ========================================================
   Autor: Renata . Tonon
   Contato: @retononcode
   Descrição: #sisnote Sistema de notas e Compromissos
   ======================================================== */

session_start();
require 'db.php';
/* ========================================================
   Autor: Renata . Tonon
   Contato: @retononcode
   Descrição: #sisnote Sistema de notas e Compromissos
   ======================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: painel.php");
        exit();
    } else {
        echo "Usuário ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        h1 {
            color: #00bfff;
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
.logo {
    width: 150px;
    height: 150px; /* Garante que a altura seja igual à largura */
    border-radius: 50%; /* Faz a imagem ficar redonda */
    overflow: hidden; /* Garante que a imagem não ultrapasse a borda */
    object-fit: cover; /* Mantém o enquadramento correto da imagem */
    margin-bottom: 15px;
}

    </style>
</head>
<body> <div class="container">
          <!-- Logo adicionada aqui -->
        <img src="logo.webp" alt="Logo" class="logo">
    <h1>Login</h1>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Senha" required><br>
        <button type="submit">Entrar</button>
    </form>
    <p>Não tem conta? <a href="register.php">Cadastre-se</a></p>    </div>
  
  
    <!-- Rodapé -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>