<?php
include 'db.php'; // Cambia 'database.php' a 'db.php' para la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['correo'];
    $password = password_hash($_POST['contraseña'], PASSWORD_BCRYPT);
    $idrol = 2; // Rol 'Usuario'

    // Preparar la consulta SQL
    $stmt = $conn->prepare("INSERT INTO usuario (nombre, correo, contraseña, idrol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nombre, $email, $password, $idrol);
    
    if ($stmt->execute()) {
        header('Location: login.php'); // Redirigir a la página de login
    } else {
        echo "Error al registrar.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar - Jaiteva</title>
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

        .container {
            width: 90%;
            max-width: 400px;
            background-color: #E5DFD1;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 20px 0;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
            text-align: left;
        }

        input {
            padding: 10px;
            border: 1px solid #DDB999;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 1rem;
        }

        button {
            padding: 10px;
            background-color: #FB852E;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #DDB999;
        }

        p {
            margin-top: 15px;
        }

        a {
            color: #FB852E;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Responsivo */
        @media (max-width: 480px) {
            header {
                padding: 15px;
            }

            .container {
                padding: 15px;
            }

            header h1 {
                font-size: 1.5rem;
            }

            input {
                font-size: 0.9rem;
            }

            button {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Registrarse</h1>
    </header>
    <div class="container">
        <form method="POST" action="register.php">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>
            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" required>
            <button type="submit">Registrarse</button>
        </form>
        <p>¿Ya tienes una cuenta? <a href="login.php">Iniciar sesión</a></p>
    </div>
</body>
</html>
