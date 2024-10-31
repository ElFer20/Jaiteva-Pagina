<?php 
session_start();
include 'db.php';

// Verificar si el ticket está presente en la URL
if (!isset($_GET['ticket'])) {
    header('Location: menu.php'); // Redirigir si no hay ticket
    exit();
}

$ticket = $_GET['ticket'];

// Obtener información del pedido utilizando el ticket
$sql = "SELECT * FROM Pedido WHERE ticket = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Error en la preparación de la consulta: " . $conn->error;
    exit();
}
$stmt->bind_param("s", $ticket);
$stmt->execute();
$result = $stmt->get_result();
$pedido = $result->fetch_assoc();

if (!$pedido) {
    echo "No se encontró el pedido.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket - Jaiteva</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="icon" type="image/png" href="img/J.png"/>
    <style>
        /* Estilos globales */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #F2EEE2;
            color: #30261A;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header {
            background-color: #FB852E;
            color: white;
            width: 100%;
            text-align: center;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .container {
            background-color: #E5DFD1;
            border-radius: 8px;
            padding: 20px;
            max-width: 600px;
            width: 90%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        h1, h2 {
            margin: 10px 0;
            color: #30261A;
        }

        p {
            margin: 8px 0;
            line-height: 1.5;
        }

        a {
            text-decoration: none;
            color: white;
            background-color: #FB852E;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
            display: inline-block;
            margin-top: 10px;
        }

        a:hover {
            background-color: #DDB999;
        }

        /* Estilo para el mensaje de puntos */
        .points-message {
            background-color: #DDB999;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 600px) {
            h1 {
                font-size: 1.5em;
            }

            h2 {
                font-size: 1.2em;
            }

            p {
                font-size: 1em;
            }

            a {
                padding: 8px 12px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Ticket de Pedido</h1>
    </header>
    <div class="container">
        <h2>Información del Pedido</h2>
        <p><strong>ID Pedido:</strong> <?= htmlspecialchars($pedido['id_pedido']) ?></p>
        <p><strong>Total:</strong> $<?= number_format((float)$pedido['total'], 2, ',', '.') ?></p>
        <p><strong>Método de Pago:</strong> <?= htmlspecialchars($pedido['metodo_pago']) ?></p>
        <p><strong>Ticket:</strong> <?= htmlspecialchars($pedido['ticket']) ?></p>
        <p><strong>Estado:</strong> <?= htmlspecialchars($pedido['estado']) ?></p>

        <!-- Mostrar mensaje y enlace a la página de éxito si el estado es "confirmado", "cocinando", "entregado" o "cancelado" -->
        <?php if (in_array($pedido['estado'], ['confirmado', 'cocinando', 'entregado', 'cancelado'])): ?>
            <div class="points-message">Ahora puedes ver tu estado del pedido aquí y reclamar tus puntos.</div>
            <a id="pedido-exito" href="pedido_exito.php?ticket=<?= urlencode($ticket) ?>">Ver Estado de Pedido</a>
        <?php endif; ?>

        <!-- Mostrar botón "Volver al Menú" si el estado es "rechazado" -->
        <?php if ($pedido['estado'] === 'rechazado'): ?>
            <a id="back-menu" href="menu.php">⬅ Volver al Menú</a>
        <?php endif; ?>
    </div>
</body>
</html>
