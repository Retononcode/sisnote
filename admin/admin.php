<?php
/* ========================================================
   Autor: Renata . Tonon
   Contato: @retononcode
   Descrição: #sisnote Sistema de notas e Compromissos
   ======================================================== */

session_start();
require '../db.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
// Autor: Renata . Tonon
// @retononcode

// Buscar os usuários no banco de dados
$stmt = $conn->prepare("SELECT id, nome, telefone, email FROM users ORDER BY id DESC");
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0a0f29;
            color: #00bfff;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background: #1a1f4e;
            border-radius: 8px;
            box-shadow: 0 0 10px #00bfff;
            text-align: left;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #1a1f4e;
        }
        th, td {
            padding: 10px;
            border: 1px solid #00bfff;
            text-align: left;
        }
        th {
            background-color: #0080ff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #131a3a;
        }
        a {
            color: #00bfff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .btn {
            padding: 5px 10px;
            background-color: red;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Painel Administrativo</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?php echo $usuario['id']; ?></td>
                <td><?php echo $usuario['nome']; ?></td>
                <td><?php echo $usuario['telefone']; ?></td>
                <td><?php echo $usuario['email']; ?></td>
                <td>
                    <a href="admin_excluir_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <a href="../index.php">Voltar ao site</a>
    </div>
  
  </body>
</html>
    
