<?php
session_start();
include 'db.php';

$total = isset($_POST['total']) ? $_POST['total'] : 0;
$id_usuario = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$pedido_exito = isset($_GET['pedido_exito']) ? $_GET['pedido_exito'] : null;

// Calcular el descuento total basado en los puntos del usuario
$sql_puntos = "SELECT puntos FROM usuario WHERE idusuario = ?";
$stmt_puntos = $conn->prepare($sql_puntos);
$stmt_puntos->bind_param("i", $id_usuario);
$stmt_puntos->execute();
$result_puntos = $stmt_puntos->get_result();
$fila_puntos = $result_puntos->fetch_assoc();
$puntos = $fila_puntos['puntos'];

// Descuento: 1 peso por cada punto acumulado
$descuento_total = $puntos; // Cambiar para que se descuente 1 peso por punto
$total_con_descuento = $total - $descuento_total; // Aplica el descuento según los puntos
if ($total_con_descuento < 0) {
    $total_con_descuento = 0; // Asegura que el total no sea negativo
}

// Generar ticket
function generateTicket() {
    return strtoupper(bin2hex(random_bytes(10)));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['metodo_pago'], $_POST['opcion_pedido'])) {
    $metodo_pago = $_POST['metodo_pago'];
    $opcion_pedido = $_POST['opcion_pedido'];
    $ticket = generateTicket();

    // Captura de datos de reserva
    if (isset($_POST['fecha_reserva']) && isset($_POST['hora_reserva']) && isset($_POST['num_personas'])) {
        $fecha_reserva = $_POST['fecha_reserva'];
        $hora_reserva = $_POST['hora_reserva'];
        $num_personas = (int)$_POST['num_personas'];
    } else {
        $fecha_reserva = null;
        $hora_reserva = null;
        $num_personas = null;
    }

    // Establecer el estado del pedido dependiendo de la opción seleccionada
    $estado = ($opcion_pedido === "reservar") ? 'en reserva' : 'pendiente';

    date_default_timezone_set('America/Argentina/Buenos_Aires'); // Ajusta la zona horaria en PHP
    // Obtener la fecha actual
    $fecha_pedido = date('Y-m-d H:i:s'); // Formato correcto para MySQL

    // Insertar el pedido en la base de datos
    $sql = "INSERT INTO Pedido (fecha_pedido, idusuario, total, metodo_pago, estado, ticket, opcion_pedido) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sidssss", $fecha_pedido, $id_usuario, $total_con_descuento, $metodo_pago, $estado, $ticket, $opcion_pedido);
    
    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
        exit(); // Termina el script si hay un error
    }

    // Obtener el ID del pedido recién creado
    $id_pedido = $stmt->insert_id;

    // Si se seleccionó la opción de reserva, insertar en la tabla 'reserva'
    if ($opcion_pedido === "reservar" && isset($fecha_reserva, $hora_reserva, $num_personas)) {
        $sqlReserva = "INSERT INTO Reserva (id_pedido, fecha_reserva, hora_reserva, num_personas) VALUES (?, ?, ?, ?)";
        $stmtReserva = $conn->prepare($sqlReserva);
        $stmtReserva->bind_param("isss", $id_pedido, $fecha_reserva, $hora_reserva, $num_personas);
        
        if (!$stmtReserva->execute()) {
            echo "Error al guardar la reserva: " . $stmtReserva->error; // Añade esta línea para depurar
        }
    }

    // Vaciar el carrito
    unset($_SESSION['carrito']);

    // Redirigir a la página del ticket
    header("Location: ticket.php?ticket=$ticket");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar - Jaiteva</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="icon" type="image/png" href="img/J.png"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #E5DFD1;
            color: #30261A;
        }
        #back-menu {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #FB852E;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        #back-menu:hover {
            background-color: #DDB999;
        }
        header {
            text-align: center;
            margin: 20px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #F2EEE2;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #30261A;
        }
        label {
            margin-top: 10px;
            display: block;
        }
        select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #DDB999;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background-color: #FB852E;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #DDB999;
        }
        @media (max-width: 600px) {
            body {
                font-size: 14px;
            }
            .container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <a id="back-menu" href="menu.php">⬅ Volver al Menú</a>
    <header>
        <h1>Pagar</h1>
    </header>
    <div class="container">
        <?php if ($pedido_exito): ?>
            <h2>¡Pedido realizado con éxito!</h2>
            <p>Gracias por tu compra. Nos pondremos en contacto cuando esté listo.</p>
        <?php else: ?>
            <h2>Total a pagar: $<?= number_format($total_con_descuento, 2) ?></h2>
            <form method="POST" action="pago.php" id="paymentForm">
                <label for="metodo_pago">Método de Pago:</label>
                <select name="metodo_pago" id="metodo_pago" required>
                    <option value="Mercado Pago">Mercado Pago</option>
                    <option value="Efectivo">Efectivo</option>
                </select>
                <input type="hidden" name="opcion_pedido" value="<?= isset($_POST['opcion_pedido']) ? htmlspecialchars($_POST['opcion_pedido']) : 'comer_acá' ?>">
                <input type="hidden" name="total" value="<?= htmlspecialchars($total) ?>">
                <?php if (isset($_POST['fecha_reserva'], $_POST['hora_reserva'])): ?>
                    <input type="hidden" name="fecha_reserva" value="<?= htmlspecialchars($_POST['fecha_reserva']) ?>">
                    <input type="hidden" name="hora_reserva" value="<?= htmlspecialchars($_POST['hora_reserva']) ?>">
                    <input type="hidden" name="num_personas" value="<?= htmlspecialchars($_POST['num_personas']) ?>">
                <?php endif; ?>
                <button type="submit">Confirmar Pago</button>
            </form>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('paymentForm').addEventListener('submit', function(event) {
            const metodoPago = document.getElementById('metodo_pago').value;
            if (metodoPago === 'Mercado Pago') {
                event.preventDefault();
                window.open('https://mpago.la/2khAYyR', '_blank'); // Abre el enlace en una nueva pestaña
                setTimeout(() => { this.submit(); }, 500); // Espera 500 ms antes de enviar el formulario
            }
        });
    </script>
</body>
</html>
