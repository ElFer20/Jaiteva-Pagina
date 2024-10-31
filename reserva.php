<?php
session_start();

// Verificar si el total fue enviado desde carrito.php y almacenarlo en sesión si no está vacío
if (isset($_POST['total']) && !empty($_POST['total'])) {
    $_SESSION['total'] = $_POST['total'];
}
// Definir la zona horaria de Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');
// Obtener la fecha y hora actual en Argentina
$fecha_actual = date('Y-m-d');
$fecha_maxima = date('Y-m-d', strtotime('+1 month'));
$hora_actual = date('H:i');
$hora_minima = date('H:i', strtotime('+20 second'));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar - Jaiteva</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="icon" type="image/png" href="img/J.png"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F2EEE2;
            color: #30261A;
            margin: 0;
            padding: 20px;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #E5DFD1;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
        }
        input[type="date"],
        input[type="time"],
        input[type="number"],
        button {
            padding: 10px;
            border: 1px solid #DDB999;
            border-radius: 5px;
        }
        button {
            background-color: #FB852E;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #DDB999;
        }
        .total-container {
            font-weight: bold;
            font-size: 1.2em;
            margin-bottom: 20px;
            color: #30261A;
        }
        .back-button {
            background-color: #30261A;
            color: #F2EEE2;
            text-align: center;
            padding: 10px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        .back-button:hover {
            background-color: #DDB999;
        }
    </style>
</head>
<body>
    <header>
        <h1>Reservar Fecha y Hora</h1>
    </header>
    <div class="container">
        <!-- Mostrar el total -->
        <div class="total-container">
            Total a pagar: $<?= isset($_SESSION['total']) ? htmlspecialchars($_SESSION['total']) : '0' ?>
        </div>
        
        <form method="POST" action="pago.php">
            <label for="fecha_reserva">Fecha:</label>
            <input type="date" name="fecha_reserva" id="fecha_reserva" min="<?= $fecha_actual ?>" max="<?= $fecha_maxima ?>" required>
            
            <label for="hora_reserva">Hora:</label>
            <input type="time" name="hora_reserva" id="hora_reserva" min="<?= $hora_minima ?>" required>
            
            <label for="num_personas">Número de Personas (Máx 15):</label>
            <input type="number" name="num_personas" id="num_personas" min="1" max="15" required>
            
            <!-- Pasar el total y la opción de pedido al siguiente formulario -->
            <input type="hidden" name="total" value="<?= htmlspecialchars($_SESSION['total']) ?>">
            <input type="hidden" name="opcion_pedido" value="<?= isset($_POST['opcion_pedido']) ? htmlspecialchars($_POST['opcion_pedido']) : 'reservar' ?>">

            <button type="submit">Continuar</button>
        </form>
        
        <!-- Botón de volver al menú -->
        <a href="menu.php" class="back-button">Volver al Menú</a>
    </div>
</body>
</html>
