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
$stmt->bind_param("s", $ticket);
$stmt->execute();
$result = $stmt->get_result();
$pedido = $result->fetch_assoc();

if (!$pedido) {
    echo "No se encontró el pedido.";
    exit();
}

// Verificar si el usuario está logueado
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $total = (float)$pedido['total'];
    $estado = $pedido['estado'];

    // Consultar el rol del usuario
    $sql_rol = "SELECT idrol FROM usuario WHERE idusuario = ?";
    $stmt_rol = $conn->prepare($sql_rol);
    $stmt_rol->bind_param("i", $user_id);
    $stmt_rol->execute();
    $result_rol = $stmt_rol->get_result();
    $usuario = $result_rol->fetch_assoc();
    $idrol = $usuario['idrol'];

    // Inicializar variable para los puntos acumulados
    $puntos_acumulados = 0;

    // Solo acumular puntos si el pedido está en estado "entregado" y el idrol es distinto de 3
    if ($estado === 'entregado' && $idrol != 3) {
        // Calcular puntos acumulados (1 punto por cada $100)
        $puntos_acumulados = floor($total / 100);

        // Actualizar los puntos en la base de datos del usuario
        $update_sql = "UPDATE usuario SET puntos = puntos + ? WHERE idusuario = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $puntos_acumulados, $user_id);
        $update_stmt->execute();

        // Actualizar los puntos en la sesión
        $_SESSION['user_points'] += $puntos_acumulados;

        // Mensaje de confirmación de puntos acumulados
        $mensaje_puntos = "Has acumulado $puntos_acumulados puntos con este pedido. ¡Gracias por elegirnos!";
    } else {
        $mensaje_puntos = "No se han acumulado puntos en este pedido.";
    }
} else {
    $mensaje_puntos = "No se han acumulado puntos en este pedido.";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Exitoso - Jaiteva</title>
    <link rel="stylesheet" href="estilos.css">
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

        h1 {
            margin: 0;
        }

        p {
            margin: 10px 0;
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

        /* Estilos para la línea de tiempo */
        .timeline {
            margin: 20px 0;
            padding: 10px;
            border-left: 4px solid #FB852E;
        }

        .timeline-item {
            margin: 10px 0;
            padding-left: 20px;
            position: relative;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #FB852E;
        }

        /* Responsive */
        @media (max-width: 600px) {
            h1 {
                font-size: 1.5em;
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
        <h1>¡Pedido Realizado con Éxito!</h1>
    </header>
    <div class="container">
        <a id="back-menu" href="menu.php">⬅ Volver al Menú</a>
        <p><strong>ID Pedido:</strong> <?= htmlspecialchars($pedido['id_pedido']) ?></p>
        <p><strong>Total:</strong> $<?= number_format($pedido['total'], 2, ',', '.') ?></p>
        <p><strong>Método de Pago:</strong> <?= htmlspecialchars($pedido['metodo_pago']) ?></p>
        <p><strong>Ticket:</strong> <?= htmlspecialchars($pedido['ticket']) ?></p>
        
        <?php if ($pedido['estado'] === 'cancelado'): ?>
            <p style="color: red;"><strong>Su pedido ha sido cancelado.</strong></p>
        <?php else: ?>
            <div class="timeline">
                <div class="timeline-item">
                    <strong>Confirmado</strong>
                </div>
                <?php if ($pedido['estado'] === 'cocinando' || $pedido['estado'] === 'entregado'): ?>
                    <div class="timeline-item">
                        <strong>Cocinando</strong>
                    </div>
                <?php endif; ?>
                <?php if ($pedido['estado'] === 'entregado'): ?>
                    <div class="timeline-item">
                        <strong>Entregado</strong>
                    </div>
                <?php endif; ?>
            </div>
            <p><?= $mensaje_puntos ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
