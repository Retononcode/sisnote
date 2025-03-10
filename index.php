<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo ao SisNote</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0a0f29;
            color: #00bfff;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .logo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            object-fit: cover;
            margin-bottom: 20px;
        }
        h1 {
            color: #00bfff;
            font-size: 2rem;
            margin-bottom: 10px;
        }
        p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        .buttons {
            display: flex;
            gap: 20px;
        }
        .button {
            padding: 10px 20px;
            background: #00bfff;
            color: #0a0f29;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1rem;
        }
        .button:hover {
            background: #0080ff;
        }
        .footer {
            position: absolute;
            bottom: 10px;
            width: 100%;
            text-align: center;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="logo.webp" alt="Logo SisNote" class="logo">
        <h1>Bem-vindo ao SisNote</h1>
        <p>Gerencie suas notas e compromissos com eficiência.</p>
        <div class="buttons">
            <a href="cadastro.php" class="button">Cadastre-se</a>
            <a href="login.php" class="button">Fazer Login</a>
        </div>
    </div>
    
    <div class="footer">
        <?php include 'includes/footer.php'; ?>
    </div>
</body>
</html>
