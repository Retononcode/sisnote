<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Mobile Responsiva</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
   /* ========================================================
   Autor: Renata . Tonon
   Contato: @retononcode
   Descrição: #sisnote Sistema de notas e Compromissos
   ======================================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #111;
            color: #fff;
        }

        .menu {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: #000;
            box-shadow: 0 0 15px rgba(0, 0, 255, 0.8);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .menu .links {
            display: flex;
            gap: 20px;
            position: relative;
            align-items: center;
        }

        .menu .links a {
            color: #00aaff;
            text-decoration: none;
            font-size: 16px;
            padding: 8px 12px;
            transition: 0.3s;
            border-radius: 5px;
        }

        .menu .links a:hover {
            background-color: #00aaff;
            color: #000;
        }

        .menu-item {
            position: relative;
        }

        .submenu {
            display: none;
            position: absolute;
            background: #000;
            box-shadow: 0 0 10px rgba(0, 0, 255, 0.8);
            border-radius: 5px;
            top: 100%;
            left: 0;
            min-width: 150px;
            text-align: left;
        }

        .submenu a {
            display: block;
            padding: 10px;
            color: #00aaff;
            text-decoration: none;
            text-align: left;
        }

        .submenu a:hover {
            background-color: #00aaff;
            color: #000;
        }

        .menu-item:hover .submenu {
            display: block;
        }

        .menu-toggle {
            display: none;
            font-size: 24px;
            background: none;
            border: none;
            color: #00aaff;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .menu {
                flex-direction: column;
                align-items: center;
                padding: 10px;
            }

            .menu .links {
                display: none;
                flex-direction: column;
                width: 100%;
                margin-top: 10px;
                gap: 10px;
                align-items: center;
                text-align: center;
            }

            .menu .links a {
                padding: 12px;
                text-align: center;
                display: block;
                border-bottom: 1px solid rgba(0, 170, 255, 0.5);
            }

            .menu-toggle {
                display: block;
            }

            .menu.open .links {
                display: flex;
            }

            .menu-item:hover .submenu {
                display: none;
            }

            .menu-item a:focus + .submenu, .submenu:hover {
                display: block;
                position: relative;
            }
        }
    </style>
</head>
<body>
    <div class="menu">
        <div class="logo">SisNote</div>
        <button class="menu-toggle" onclick="toggleMenu()">☰</button>
        <div class="links">
            <a href="index.php">Início</a>
            <div class="menu-item">
                <a href="#">Notas ▼</a>
                <div class="submenu">
                    <a href="notas.php">Ver Notas</a>
                </div>
            </div>
            <div class="menu-item">
                <a href="#">Compromissos ▼</a>
                <div class="submenu">
                    <a href="compromissos.php">Ver Compromissos</a>
                    <a href="add_compromissos.php">Adicionar Compromisso</a>
                </div>
            </div>
            <a href="https://www.linkedin.com/in/retononcode/">Contato</a>
        </div>
    </div>
    <script>
/* ========================================================
   Autor: Renata . Tonon
   Contato: @retononcode
   Descrição: #sisnote Sistema de notas e Compromissos
   ======================================================== */
        function toggleMenu() {
            document.querySelector(".menu").classList.toggle("open");
        }
    </script>
</body>
</html>
