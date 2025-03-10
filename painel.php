<?php

/* ========================================================
   Autor: Renata . Tonon
   Contato: @retononcode
   Descrição: Código otimizado e estruturado
   ======================================================== */

session_start();
require 'db.php'; // Conexão com o banco de dados

// Redirecionamento caso não esteja logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Criar nota
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_note'])) {
    $stmt = $conn->prepare("INSERT INTO notes (user_id, content) VALUES (?, '')");
    $stmt->execute([$user_id]);
    echo json_encode(['note_id' => $conn->lastInsertId()]);
    exit();
}

// Atualizar nota
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_note'])) {
    $stmt = $conn->prepare("UPDATE notes SET content = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$_POST['content'], $_POST['note_id'], $user_id]);
    exit();
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área de Trabalho</title>
  <style>/* =================== BOTÕES =================== */
.btn {
    background: #00bfff;
    color: #fff;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    border-radius: 5px;
    transition: 0.3s;
}

.btn:hover {
    background: #0080ff;
}

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
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            background: #00bfff;
            color: #0a0f29;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }
        .button:hover {
            background: #0080ff;
        }
        .note-icon {
            color: #ffcc00;
            font-size: 24px;
        }

/* =================== TABELAS =================== */
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
       </style>
</head>
<body>
  <?php include 'includes/menu.php'; ?><br><br>
    <br><br><center><h1>Área de Trabalho</h1>
    <div class="container">
        <h2>Escolha uma opção</h2>
        <a href="notas.php" class="button"><span class="note-icon">📝</span> Acessar Bloco de Notas</a>
        <a href="compromissos.php" class="button"><span class="calendar-icon">📅</span> Acessar Compromissos</a>
    </div>

    <!-- Rodapé -->
    <?php include 'includes/footer.php'; ?>  </center>
</body>
</html>
