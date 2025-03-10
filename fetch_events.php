<?php
/* ========================================================
   Autor: Renata . Tonon
   Contato: @retononcode
   Descrição: #sisnote Sistema de notas e Compromissos
   ======================================================== */
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit("Acesso negado");
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, title, description, date, color FROM events WHERE user_id = ?");
$stmt->execute([$user_id]);

$events = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $events[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'description' => $row['description'],
        'start' => $row['date'],
        'color' => $row['color']
    ];
}

echo json_encode($events);
?>
