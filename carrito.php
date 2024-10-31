<?php
session_start();
$idrol = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null; // Corrige a 'user_role'
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Jaiteva</title>
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
            text-align: center;
            padding: 20px;
            background-color: #FB852E;
            color: #fff;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .container {
            width: 90%;
            max-width: 800px;
            padding: 20px;
            background-color: #E5DFD1;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 1rem;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #DDB999;
        }

        th {
            background-color: #FB852E;
            color: #fff;
        }

        td {
            background-color: #F2EEE2;
        }

        .total-row td {
            font-weight: bold;
            background-color: #DDB999;
        }

        button, select {
            padding: 10px 15px;
            font-size: 1rem;
            color: #fff;
            background-color: #FB852E;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover, select:hover {
            background-color: #DDB999;
        }

        #back-menu {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #FB852E;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        #back-menu:hover {
            background-color: #DDB999;
        }
    </style>
</head>
<body>
    <a id="back-menu" href="menu.php">⬅ Volver al Menú</a>
    <header>
        <h1>Carrito de Compras</h1>
    </header>
    <div class="container">
        <?php
        include 'db.php';

        if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
            $total = 0;

            echo "<table>";
            echo "<tr><th>Nombre del plato</th><th>Cantidad</th><th>Precio unitario</th><th>Total</th></tr>";

            foreach ($_SESSION['carrito'] as $item) {
                $nombre = isset($item['nombre']) ? $item['nombre'] : 'Nombre no disponible';
                $cantidad = isset($item['cantidad']) ? $item['cantidad'] : 0;
                $precio = isset($item['precio']) ? $item['precio'] : 0;
                $subtotal = $cantidad * $precio;
            
                echo "<tr>";
                echo "<td>" . $nombre . "</td>";
                echo "<td id='cantidad-$nombre'>" . $cantidad . "</td>";
                echo "<td>$" . number_format($precio, 2) . "</td>";
                echo "<td>$" . number_format($subtotal, 2) . "</td>";
                echo "</tr>";
            
                $total += $subtotal;
                        
            }

            echo "<tr class='total-row'><td colspan='3'>Total</td><td>$" . number_format($total, 2) . "</td></tr>";
            echo "</table>";

            echo "<form method='POST' action='vaciar_carrito.php'>";
            echo "<button type='submit'>Vaciar Carrito</button>";
            echo "</form>";

// Formulario para seleccionar la opción de pedido
echo "<form method='POST' action='pago.php' id='pedido-form'>";
echo "<h2>Opciones de Pedido:</h2>";
echo "<select name='opcion_pedido' id='opcion-pedido' required>";
echo "<option value='comer_acá'>Para Comer Acá</option>";
echo "<option value='para_llevar'>Para Llevar</option>";
echo "<option value='reservar'>Para Reservar</option>";
echo "</select>";



echo "<input type='hidden' name='total' value='$total'>";
echo "<button type='submit'>Proceder al Pago</button>";
echo "</form>";

        } else {
            echo "<p>El carrito está vacío.</p>";
        }
        ?>
    </div>

    <script>
document.getElementById('pedido-form').addEventListener('submit', function(event) {
    const opcionPedido = document.getElementById('opcion-pedido').value;
    const idRolUsuario = <?= json_encode($idrol) ?>;  // Usa el rol almacenado en la sesión de PHP

    // Si la opción seleccionada es "reservar"
    if (opcionPedido === 'reservar') {
        event.preventDefault(); // Prevenir el envío del formulario

        if (idRolUsuario === "3") {
            alert("Si quiere reservar su pedido, tiene que tener una cuenta iniciada."); // Mensaje de error
        } else {
            // Si el rol es diferente a "3", redirige a reserva.php
            const formData = new FormData();
            formData.append('total', <?= $total ?>);
            formData.append('opcion_pedido', opcionPedido);

            fetch('reserva.php', {
                method: 'POST',
                body: formData
            }).then(() => {
                window.location.href = 'reserva.php'; // Redirigir después de la reserva
            });
        }
    }
});

// Función para actualizar la cantidad de un item en el carrito
function updateQuantity(nombre, delta) {
    const cantidadElement = document.getElementById('cantidad-' + nombre);
    let cantidad = parseInt(cantidadElement.innerText);
    const precioUnitario = parseFloat(cantidadElement.closest('tr').querySelector('td:nth-child(3)').innerText.replace('$', '').replace(',', ''));

    if (cantidad + delta < 1) {
        removeItem(nombre);
        return;
    }

    cantidad += delta;
    cantidadElement.innerText = cantidad;

    // Actualiza el subtotal de este item
    const subtotal = cantidad * precioUnitario;
    cantidadElement.closest('tr').querySelector('td:nth-child(4)').innerText = '$' + subtotal.toFixed(2);

    // Actualiza el total del carrito
    updateTotal();

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'actualizar_carrito.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Cantidad actualizada');
        }
    };
    xhr.send(JSON.stringify({ nombre: nombre, cantidad: cantidad }));
}

// Función para eliminar un item del carrito
function removeItem(nombre) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'eliminar_item.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Item eliminado');
            location.reload();
        }
    };
    xhr.send(JSON.stringify({ nombre: nombre }));
}

// Función para actualizar el total del carrito
function updateTotal() {
    const totalElement = document.querySelector('.total-row td:nth-child(4)');
    let total = 0;

    // Suma todos los subtotales
    document.querySelectorAll('tr').forEach(row => {
        const subtotal = parseFloat(row.querySelector('td:nth-child(4)').innerText.replace('$', '').replace(',', ''));
        if (!isNaN(subtotal)) {
            total += subtotal;
        }
    });

    totalElement.innerText = '$' + total.toFixed(2); // Actualiza el total en la tabla
}

    </script>
</body>
</html>