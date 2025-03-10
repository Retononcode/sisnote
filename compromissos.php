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

// Definir paginação
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
/* ========================================================
   Autor: Renata . Tonon
   Contato: @retononcode
   Descrição: #sisnote Sistema de notas e Compromissos
   ======================================================== */
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

// Campo de busca
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$whereClause = "WHERE user_id = ?";
$params = [$user_id];

if ($search) {
    $whereClause .= " AND (title LIKE ? OR client_name LIKE ? OR description LIKE ? OR date LIKE ? OR time LIKE ? )";
    $params = array_merge($params, array_fill(0, 5, "%$search%"));
}

// Listar compromissos
$stmt = $conn->prepare("SELECT * FROM events WHERE user_id = ? ORDER BY date ASC, time ASC");
$stmt->execute([$user_id]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Buscar compromissos paginados
$stmt = $conn->prepare("SELECT * FROM events WHERE user_id = ? ORDER BY date ASC, time ASC LIMIT ? OFFSET ?");
$stmt->execute([$user_id, $limit, $offset]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Contar total de compromissos
$stmtTotal = $conn->prepare("SELECT COUNT(*) FROM events WHERE user_id = ?");
$stmtTotal->execute([$user_id]);
$totalEvents = $stmtTotal->fetchColumn();
$totalPages = ceil($totalEvents / $limit);
/* ========================================================
   Autor: Renata . Tonon
   Contato: @retononcode
   Descrição: #sisnote Sistema de notas e Compromissos
   ======================================================== */
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  // Autor: Renata . Tonon
// @retononcode

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compromissos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0a0f29;
            color: #00bfff;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: auto;
            background: #1a1f4e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px #00bfff;
        }
        .event-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 10px;
        }
        .event {
            border: 1px solid #00bfff;
            padding: 15px;
            background: #1a1f4e;
            color: #00bfff;
            border-radius: 5px;
            text-align: left;
        }
        .pagination {
            margin-top: 20px;
        }
        .pagination a {
            padding: 10px;
            background: #00bfff;
            color: #0a0f29;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        .pagination a:hover {
            background: #0080ff;
        }
        @media (max-width: 600px) {
            .event-grid {
                grid-template-columns: repeat(2, 1fr);
            }
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
        .close-modal {
            background: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
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
             .search-box {
            margin-bottom: 20px;
        }
        .search-box input {
            padding: 10px;
            width: 70%;
            border: 1px solid #00bfff;
            background: transparent;
            color: #00bfff;
            border-radius: 5px;
            text-align: center;
        }
        .search-box button {
            padding: 10px;
            background: #00bfff;
            color: #0a0f29;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-box button:hover {
            background: #0080ff;
        }
        @media (max-width: 600px) {
            .event-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
  <?php include 'includes/menu.php'; ?>
<br><br><br><br><br><br><br><br><br><br>

   <h1>Calendário de Compromissos</h1>
    <div id="calendar"></div>
    <div class="modal-overlay"></div>
    <div class="modal" id="event-modal">
        <h3 id="modal-title"></h3>
        <p id="modal-client"></p>
        <p id="modal-description"></p>
        <p id="modal-date"></p>
        <button class="close-modal" onclick="closeModal()">Fechar</button>
    </div>
  
        
      
              <?php include 'includes/excluir_compromisso.php'; ?>
      
      
    <!-- Rodapé -->
    <?php include 'includes/footer.php'; ?>
    <script>

        function openModal(title, client, description, date) {
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-client').textContent = "Cliente: " + client;
            document.getElementById('modal-description').textContent = description;
            document.getElementById('modal-date').textContent = "Data: " + date;
            document.querySelector('.modal-overlay').style.display = 'block';
            document.getElementById('event-modal').style.display = 'block';
        }

        function closeModal() {
            document.querySelector('.modal-overlay').style.display = 'none';
            document.getElementById('event-modal').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'pt-br',
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listYear'
                },
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
    <div class="container">


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
      
      document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'pt-br', // Define o idioma para português do Brasil
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
