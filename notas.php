<?php
session_start();
require 'db.php'; // Conexão com o banco de dados

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

// Excluir nota
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_note'])) {
    $stmt = $conn->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
    $stmt->execute([$_POST['note_id'], $user_id]);
    echo json_encode(['status' => 'success']);
    exit();
}

// Upload de arquivos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $note_id = $_POST['note_id'];
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $filename = basename($_FILES['file']['name']);
    $target_file = $target_dir . time() . "_" . $filename;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO note_files (note_id, file_path) VALUES (?, ?)");
        $stmt->execute([$note_id, $target_file]);
        echo json_encode(['status' => 'success', 'file_path' => $target_file]);
    } else {
        echo json_encode(['status' => 'error']);
    }
    exit();
}

// Listar notas
$stmt = $conn->prepare("SELECT * FROM notes WHERE user_id = ?");
$stmt->execute([$user_id]);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloco de Notas</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0a0f29;
            color: #00bfff;
            text-align: center;
        }
        .note {
            border: 1px solid #00bfff;
            padding: 10px;
            margin: 10px 0;
            background: #1a1f4e;
            color: #00bfff;
            border-radius: 5px;
            position: relative;
        }
        .delete { color: red; cursor: pointer; position: absolute; top: 5px; right: 10px; }
        .upload-btn { margin-top: 5px; }
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
      body {
    font-family: Arial, sans-serif;
    background-color: #0a0f29;
    color: #00bfff;
    text-align: center;
    margin: 0;
    padding: 0;
}

#notes {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    padding: 20px;
}

.note {
    width: 250px;
    min-height: 150px;
    background: #ffeb3b;
    color: #000;
    padding: 15px;
    margin: 10px;
    border-radius: 5px;
    box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);
    position: relative;
    transform: rotate(-3deg);
    font-family: 'Comic Sans MS', cursive, sans-serif;
}

.note:nth-child(even) {
    transform: rotate(3deg);
}

.note textarea {
    width: 100%;
    height: 100px;
    background: transparent;
    border: none;
    color: #000;
    resize: none;
    font-size: 14px;
    font-family: inherit;
}

.note textarea:focus {
    outline: none;
}

.delete {
    color: red;
    cursor: pointer;
    position: absolute;
    top: 5px;
    right: 10px;
    font-weight: bold;
}

.upload-btn {
    margin-top: 5px;
}

.button, #new-note {
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

.button, #new-note:hover {
    background: #0080ff;
}

@media (max-width: 600px) {
    #notes {
        flex-direction: column;
        align-items: center;
    }
    .note {
        width: 90%;
    }
}
    </style>
</head>
<body>
  <?php include 'includes/menu.php'; ?>
<br><br><br><br><br><br><br><br>
    <h1>Suas Notas</h1>
  <input type="text" id="search" placeholder="Pesquisar nota..." style="width: 80%; padding: 10px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #00bfff;">
<br>
    <button id="new-note">Nova Nota</button><br>
    <div id="notes">
        <?php foreach ($notes as $note): ?>
            <div class="note" data-id="<?= $note['id'] ?>">
                <textarea class="content" style="background: transparent; color: #00bfff; border: none; width: 100%;">
                    <?= htmlspecialchars($note['content']) ?>
                </textarea>
                <span class="delete">[Excluir]</span>
                <form class="upload-form" enctype="multipart/form-data">
                    <input type="file" class="file-upload" data-note-id="<?= $note['id'] ?>">
                </form>
                <div class="attachments">
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM note_files WHERE note_id = ?");
                    $stmt->execute([$note['id']]);
                    $files = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($files as $file) {
                        echo '<a href="' . $file['file_path'] . '" target="_blank">Ver Arquivo</a><br>';
                    }
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
  
  
    <!-- Rodapé -->
    <?php include 'includes/footer.php'; ?>
    <script>
        $(document).ready(function() {
            $('#new-note').click(function() {
                $.post('', { create_note: 1 }, function(response) {
                    let note = JSON.parse(response);
                    $('#notes').append(`
                        <div class='note' data-id='${note.note_id}'>
                            <textarea class='content' style='background: transparent; color: #00bfff; border: none; width: 100%;'></textarea>
                            <span class='delete'>[Excluir]</span>
                            <form class='upload-form' enctype='multipart/form-data'>
                                <input type='file' class='file-upload' data-note-id='${note.note_id}'>
                            </form>
                            <div class='attachments'></div>
                        </div>
                    `);
                });
            });
            
            $(document).on('blur', '.content', function() {
                let note_id = $(this).closest('.note').data('id');
                let content = $(this).val();
                $.post('', { update_note: 1, note_id, content });
            });
            
            $(document).on('click', '.delete', function() {
                let noteElement = $(this).closest('.note');
                let note_id = noteElement.data('id');
                $.post('', { delete_note: 1, note_id }, function(response) {
                    let result = JSON.parse(response);
                    if (result.status === 'success') {
                        noteElement.fadeOut(300, function() { $(this).remove(); });
                    }
                });
            });
            
            $(document).on('change', '.file-upload', function() {
                let fileInput = $(this);
                let note_id = fileInput.data('note-id');
                let formData = new FormData();
                formData.append('file', fileInput[0].files[0]);
                formData.append('note_id', note_id);
                $.ajax({
                    url: '',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        let result = JSON.parse(response);
                        if (result.status === 'success') {
                            fileInput.closest('.note').find('.attachments').append(`<a href='${result.file_path}' target='_blank'>Ver Arquivo</a><br>`);
                        }
                    }
                });
            });
        });
      $(document).ready(function() {
    $('#search').on('keyup', function() {
        let searchTerm = $(this).val().toLowerCase();
        
        $('.note').each(function() {
            let noteContent = $(this).find('.content').val().toLowerCase();
            
            if (noteContent.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});

    </script>
</body>
</html>
