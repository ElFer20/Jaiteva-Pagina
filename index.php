<!DOCTYPE html> 
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante Jaiteva</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="icon" type="image/png" href="img/J.png"/>
    <style>
        /* Estilos globales */
        body {
            font-family: Arial, sans-serif;
            background-color: #F2EEE2;
            color: #30261A;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        header {
            background-color: #FB852E;
            color: #fff;
            text-align: center;
            padding: 20px;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
        }

        nav {
            margin-top: 15px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #DDB999;
        }

        .container {
            width: 90%;
            max-width: 800px;
            background-color: #E5DFD1;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 20px 0;
        }

        .container h2 {
            margin: 0 0 10px;
        }

        .container p {
            margin: 0;
            font-size: 1.1rem;
        }

        /* Responsivo */
        @media (max-width: 768px) {
            header {
                padding: 15px;
            }

            nav a {
                font-size: 0.9rem;
            }

            .container {
                padding: 15px;
            }
        }

        @media (max-width: 480px) {
            header {
                padding: 10px;
            }

            nav a {
                font-size: 0.8rem;
            }

            .container {
                padding: 10px;
            }

            .container h2 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Bienvenido a Jaiteva</h1>
        <nav>
            <a href="invitado_login.php">Iniciar sin cuenta</a>
            <a href="register.php">Registrarse</a>
            <a href="login.php">Iniciar Sesión</a>
        </nav>
    </header>
    <div class="container">
        <h2>Disfruta de la mejor comida con el mejor servicio</h2>

    </div>
</body>
</html>
