<?php 
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['correo'];
    $password = $_POST['contraseña'];

    // Cambia la consulta para usar la tabla 'usuario'
    $stmt = $conn->prepare("SELECT * FROM usuario WHERE correo = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['contraseña'])) {
            $_SESSION['user_id'] = $user['idusuario'];
            $_SESSION['user_role'] = $user['idrol']; // Guarda el rol de usuario en la sesión
            $_SESSION['user_points'] = $user['puntos'] ?? 0;

            // Redirigir a operador.php si es Admin, si no, a menu.php
            if ($user['idrol'] == 1) {
                header('Location: operador.php');
            } else {
                header('Location: menu.php');
            }
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Jaiteva</title>
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
        <h1>Iniciar Sesión</h1>
    </header>
    <div class="container">
        <form method="POST" action="login.php">
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>
            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <p>¿No tienes una cuenta? <a href="register.php">Registrarse</a></p>
    </div>
</body>
</html>
