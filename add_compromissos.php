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
// Autor: Renata . Tonon
// @retononcode

// Listar compromissos
$stmt = $conn->prepare("SELECT * FROM events WHERE user_id = ? ORDER BY date ASC, time ASC");
$stmt->execute([$user_id]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Excluir compromisso
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_event'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM events WHERE id = ? AND user_id = ?");
        $stmt->execute([$_POST['event_id'], $user_id]);
        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compromissos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <style>
          html, body {
          height: 100%;
          margin: 0;
          padding: 0;
          overflow-x: hidden;
        }
          .container {
           min-height: 100vh; /* Faz a altura ser pelo menos do tamanho da tela */
        }
       html {
    overflow-y: scroll;
}

        .container {
            width: 80%;
            margin: auto;
            background: #1a1f4e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px #00bfff;
        }
        .event {
            border: 1px solid #00bfff;
            padding: 15px;
            margin: 10px 0;
            background: #1a1f4e;
            color: #00bfff;
            border-radius: 5px;
            text-align: left;
        }
        button {
            background: #00bfff;
            color: #0a0f29;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background: #0080ff;
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #00bfff;
            background: transparent;
            color: #00bfff;
            border-radius: 5px;
            text-align: center;
        }
        #calendar {
            max-width: 800px;
            margin: 20px auto;
            background: #1a1f4e;
            border-radius: 5px;
            padding: 10px;
        }
              .event {
            border: 1px solid #00bfff;
            padding: 15px;
            margin: 10px 0;
            background: #1a1f4e;
            color: #00bfff;
            border-radius: 5px;
            text-align: left;
        }
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 999;
        }
        .modal {
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
            width: 50%;
        }
        .close-modal {
            background: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        #calendar {
            max-width: 800px;
            margin: 20px auto;
            background: #1a1f4e;
            border-radius: 5px;
            padding: 10px;
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
        .calendar-icon {
            color: #ff5733;
            font-size: 24px;
        }
              .delete-event {
            position: absolute;
            top: 10px;
            right: 10px;
            background: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>
  <?php include 'includes/menu.php'; ?>
<br><br><br><br><br><br><br><br><center>
    <h1>Seus Compromissos</h1>
   <a href="compromissos.php" class="button"><span class="calendar-icon">📅</span> ver calendario de Compromissos</a>
   <a href="add_compromissos.php" class="button"><span class="calendar-icon">📅</span> Adicionar Compromissos</a>
   <a href="includes/excluir_compromisso.php" id="delete-all" class="button" style="background: red;"><span class="calendar-icon">❌</span> Excluir Todos Compromissos</a>


    <div class="container">
        <h2>Adicionar Compromisso</h2>
        <form id="event-form">
            <input type="text" name="client_name" placeholder="Nome do Cliente" required><br>
            <input type="text" name="title" placeholder="Título do Compromisso" required><br>
            <input type="text" name="description" placeholder="Descrição" required><br>
            <input type="date" name="date" required><br>
            <input type="time" name="time" required><br>
            <button type="submit">Salvar Compromisso</button>
        </form>
    </div>

   

    <div class="container">
        <h2>Lista de Compromissos</h2>
        <?php foreach ($events as $event): ?>
            <div class="event">
                <h3><?= htmlspecialchars($event['title']) ?></h3>
                <p><strong>Cliente:</strong> <?= htmlspecialchars($event['client_name']) ?></p>
                <p><strong>Descrição:</strong> <?= htmlspecialchars($event['description']) ?></p>
                <p><strong>Data:</strong> <?= htmlspecialchars($event['date']) ?> às <?= htmlspecialchars($event['time']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Rodapé -->
    <?php include 'includes/footer.php'; ?></center>
    <script>
        document.getElementById('event-form').addEventListener('submit', function(event) {
            event.preventDefault();
            let formData = new FormData(this);
            formData.append('create_event', 1);
            fetch('', {
                method: 'POST',
                body: formData
            }).then(response => response.json()).then(data => {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    alert("Erro ao salvar: " + data.message);
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    <?php foreach ($events as $event): ?>
                    {
                        title: "<?= htmlspecialchars($event['title']) ?>",
                        start: "<?= htmlspecialchars($event['date']) ?>",
                        description: "<?= htmlspecialchars($event['description']) ?>",
                    },
                    <?php endforeach; ?>
                ],
                eventClick: function(info) {
                    alert("Compromisso: " + info.event.title + "\nDescrição: " + info.event.extendedProps.description);
                }
            });
            calendar.render();
        });
    </script>
   <script>
        function openModal(title, client, description, date) {
            $('#modal-title').text(title);
            $('#modal-client').text("Cliente: " + client);
            $('#modal-description').text(description);
            $('#modal-date').text("Data: " + date);
            $('.modal-overlay, .modal').fadeIn();
        }

        function closeModal() {
            $('.modal-overlay, .modal').fadeOut();
        }

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    <?php foreach ($events as $event): ?>
                    {
                        title: "<?= htmlspecialchars($event['title']) ?>",
                        start: "<?= htmlspecialchars($event['date']) ?>",
                        extendedProps: {
                            client: "<?= htmlspecialchars($event['client_name']) ?>",
                            description: "<?= htmlspecialchars($event['description']) ?>"
                        }
                    },
                    <?php endforeach; ?>
                ],
                eventClick: function(info) {
                    openModal(info.event.title, info.event.extendedProps.client, info.event.extendedProps.description, info.event.start.toISOString().split('T')[0]);
                }
            });
            calendar.render();
        });
    </script>
</body>
</html>