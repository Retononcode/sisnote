<?php
/* ========================================================
   Autor: Renata . Tonon
   Contato: @retononcode
   Descrição: #sisnote Sistema de notas e Compromissos
   ======================================================== */

session_start();
require 'db.php'; // Conexão com o banco de dados

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$limit = 6; // Número de compromissos por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Criar compromisso
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_event'])) {
    try {
        $stmt = $conn->prepare("INSERT INTO events (user_id, client_name, title, description, date, time) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $_POST['client_name'], $_POST['title'], $_POST['description'], $_POST['date'], $_POST['time']]);
        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit();
}

// Excluir compromisso
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_event'], $_POST['event_id'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM events WHERE id = ? AND user_id = ?");
        $stmt->execute([$_POST['event_id'], $user_id]);
        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit();
}

// Listar compromissos com paginação
$stmt = $conn->prepare("SELECT * FROM events WHERE user_id = ? ORDER BY date ASC, time ASC LIMIT ? OFFSET ?");
$stmt->execute([$user_id, $limit, $offset]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Contagem total de compromissos para paginação
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM events WHERE user_id = ?");
$stmt->execute([$user_id]);
$total_events = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_events / $limit);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Compromissos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0a0f29;
            color: #00bfff;
            text-align: center;
        }
        .container {
            width: 90%;
            max-width: 1000px;
            margin: auto;
            background: #1a1f4e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px #00bfff;
        }
        .events-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .event {
            width: 48%;
            border: 1px solid #00bfff;
            padding: 15px;
            margin: 10px 0;
            background: #1a1f4e;
            color: #00bfff;
            border-radius: 5px;
            text-align: left;
            position: relative;
            box-sizing: border-box;
        }
        .delete-event {
            position: absolute;
            top: 10px;
            right: 10px;
            background: red;
            color: white;
            border: none;
            padding: 5px;
            cursor: pointer;
            border-radius: 50%;
            font-size: 14px;
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
        .modal-overlay1 {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 999;
        }
        .modal1 {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #1a1f4e;
            padding: 20px;
            border-radius: 5px;
            color: #00bfff;
            box-shadow: 0 0 15px #00bfff;
            z-index: 1000;
            width: 300px;
        }
        .modal1 button {
            margin: 10px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .confirm-delete {
            background: red;
            color: white;
        }
        .cancel-delete {
            background: #00bfff;
            color: #0a0f29;
        }
        @media screen and (max-width: 768px) {
            .container {
                width: 95%;
            }
            .events-container {
                flex-direction: column;
            }
            .event {
                width: 100%;
            }
        }
        @media (max-width: 600px) {
            .event-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }</style>
</head>
<body>
    <?php include 'menu.php'; ?>
<br><br><br><br><br><br><br><br>
    <h1>Seus Compromissos</h1>

    <div class="container">
        <h2>Lista de Compromissos</h2>
        <div class="events-container">
            <?php foreach ($events as $event): ?>
                <div class="event" data-id="<?= $event['id'] ?>">
                    <button class="delete-event" data-id="<?= $event['id'] ?>">✖</button>
                    <h3><?= htmlspecialchars($event['title']) ?></h3>
                    <p><strong>Cliente:</strong> <?= htmlspecialchars($event['client_name']) ?></p>
                    <p><strong>Descrição:</strong> <?= htmlspecialchars($event['description']) ?></p>
                    <p><strong>Data:</strong> <?= htmlspecialchars($event['date']) ?> às <?= htmlspecialchars($event['time']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    </div>

    <div class="modal-overlay1"></div>
    <div class="modal1">
        <h3>Confirmar Exclusão</h3>
        <p>Tem certeza que deseja excluir este compromisso?</p>
        <button class="confirm-delete">Excluir</button>
        <button class="cancel-delete">Cancelar</button>
    </div>

    <script>
        let eventIdToDelete = null;

        document.querySelectorAll('.delete-event').forEach(button => {
            button.addEventListener('click', function() {
                eventIdToDelete = this.getAttribute('data-id');
                document.querySelector('.modal-overlay1').style.display = 'block';
                document.querySelector('.modal1').style.display = 'block';
            });
        });

        document.querySelector('.confirm-delete').addEventListener('click', function() {
            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `delete_event=1&event_id=${eventIdToDelete}`
            }).then(() => location.reload());
        });

        document.querySelector('.cancel-delete').addEventListener('click', function() {
            document.querySelector('.modal-overlay1').style.display = 'none';
            document.querySelector('.modal1').style.display = 'none';
        });
    </script>
</body>
</html>
