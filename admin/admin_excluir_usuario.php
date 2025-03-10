<?php
/* ========================================================
   Autor: Renata . Tonon
   Contato: @retononcode
   Descrição: #sisnote Sistema de notas e Compromissos
   ======================================================== */
require '../db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    
    echo "<script>alert('Usuário excluído com sucesso!'); window.location.href='admin.php';</script>";
} else {
    echo "<script>alert('ID inválido!'); window.location.href='admin.php';</script>";
}
?>

