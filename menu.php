<?php
session_start();
include 'db.php'; // Conexión a la base de datos

// Verificar si se solicitó cerrar sesión
if (isset($_POST['logout'])) {
    session_destroy(); // Destruir la sesión
    header("Location: index.php"); // Redirigir a la página de inicio
    exit();
}

// Obtener el ID del usuario actual
$id_usuario = $_SESSION['user_id'] ?? null;



// Obtener puntos del usuario
$puntos = 0;
if ($id_usuario) {
    $sql_puntos = "SELECT puntos FROM usuario WHERE idusuario = ?";
    $stmt_puntos = $conn->prepare($sql_puntos);
    $stmt_puntos->bind_param("i", $id_usuario);
    $stmt_puntos->execute();
    $result_puntos = $stmt_puntos->get_result();
    if ($result_puntos->num_rows > 0) {
        $row_puntos = $result_puntos->fetch_assoc();
        $puntos = $row_puntos['puntos'];
    }
}

// Obtener el filtro seleccionado (celiaco, vegano, vegetariano, sin condición alimenticia)
$filtro = isset($_GET['condicion']) ? $_GET['condicion'] : '';

// Obtener el término de búsqueda
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

// Modificar la consulta SQL en base al filtro y la búsqueda
$sql = "SELECT * FROM Menu WHERE 1=1";

if ($filtro) {
    $sql .= " AND condicion_alimenticia = ?";
}

if ($busqueda) {
    $sql .= " AND nombre LIKE ?";
}

$stmt = $conn->prepare($sql);

if ($filtro && $busqueda) {
    $busquedaParam = "%$busqueda%"; // Se usa una variable separada para el LIKE
    $stmt->bind_param("ss", $filtro, $busquedaParam);
} elseif ($filtro) {
    $stmt->bind_param("s", $filtro);
} elseif ($busqueda) {
    $busquedaParam = "%$busqueda%"; // Se usa una variable separada para el LIKE
    $stmt->bind_param("s", $busquedaParam);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú - Jaiteva</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="icon" type="image/png" href="img/J.png"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #F2EEE2;
            color: #30261A;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #FB852E;
            padding: 20px;
            text-align: center;
            color: #E5DFD1;
        }
        .container {
            max-width: 1200px; 
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            background-color: #E5DFD1;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .menu-section {
            margin-bottom: 40px;
            padding: 20px;
            border-radius: 8px;
            background-color: #F2EEE2;
            border: 2px solid #DDB999;
        }
        .menu-section h2 {
            text-align: center;
            color: #FB852E;
            margin-bottom: 15px;
            font-size: 24px;
            border-bottom: 2px solid #FB852E;
            padding-bottom: 10px;
        }
        .menu-items {
            display: flex;
            flex-direction: column; 
            gap: 15px; 
        }
        .menu-item {
            padding: 15px;
            background-color: #DDB999;
            border: 1px solid #FB852E;
            border-radius: 5px;
            display: flex;
            flex-direction: column; 
            align-items: flex-start; 
            width: calc(100% - 30px); 
            margin: 0 auto; 
        }
        .menu-item h3 {
            margin: 0;
            color: #30261A;
            font-size: 20px;
        }
        .menu-item p {
            margin: 5px 0;
        }
        .menu-item form {
            margin-top: 10px; 
            width: 100%; 
        }
        .menu-item button {
            background-color: #FB852E;
            color: #E5DFD1;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%; 
        }
        .menu-item button:hover {
            background-color: #D57C2C; 
        }
        .filter-button {
            background-color: white;
            border: 2px solid #FB852E;
            color: #FB852E;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }
        .filter-button.selected {
            background-color: #FB852E;
            color: white;
        }
        #search-container {
            text-align: center;
            margin: 20px 0;
        }
        #search-input {
            padding: 10px;
            width: 60%;
            border: 2px solid #FB852E;
            border-radius: 5px;
            font-size: 16px;
        }
        #search-button {
            background-color: #FB852E;
            color: #E5DFD1;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-left: 5px;
        }
        #search-button:hover {
            background-color: #D57C2C;
        }
        #cart-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none;
        }
        #cart-button button {
            background-color: #FB852E;
            color: #E5DFD1;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }
        #cart-button button:hover {
            background-color: #D57C2C; 
        }
        #points {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #FB852E;
            color: #E5DFD1;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
        }
        footer {
    width: 100%;
    background-color: #30261A;
    color: #E5DFD1;
    padding: 20px;
    text-align: center;
    font-size: 0.9rem;
    box-sizing: border-box; /* Asegura que el padding se incluya en el ancho total */
    overflow: hidden; /* Previene el desbordamiento del contenido */
}

.footer-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    max-width: 1200px; /* Límite de ancho para el contenido */
    margin: 0 auto; /* Centra el contenido en el footer */
}

.footer-content p {
    margin: 5px 0;
    color: #DDB999;
}

.social-media {
    display: flex;
    gap: 15px;
    margin-top: 10px;
}

.social-media a {
    color: #FB852E;
    font-size: 1.5rem;
    transition: color 0.3s ease;
}

.social-media a:hover {
    color: #DDB999;
}

.location {
    margin-top: 10px;
    font-size: 0.9rem;
    color: #F2EEE2;
}

.location a {
    color: #FB852E;
    text-decoration: none;
    font-size: 1rem;
    transition: color 0.3s ease;
}

.location a:hover {
    color: #DDB999;
}

@media (min-width: 600px) {
    .footer-content {
        flex-direction: row;
        justify-content: space-between;
        /* max-width: 1200px; se mueve al footer-content */
        margin: 0 auto; /* Asegura que el contenido se mantenga centrado */
    }

    .social-media, .location {
        margin-top: 0;
    }
}

    </style>
</head>
<body>
<header>
    <h1>Menú de Jaiteva</h1>
    <?php if ($id_usuario && $puntos > 0): ?>
        <div id="points">Puntos acumulados: <?= $puntos ?></div>
    <?php endif; ?>
    
    <form method="POST" action="menu.php" style="display: inline;">
        <button type="submit" name="logout" style="background-color: #D57C2C; color: #E5DFD1; border: none; padding: 10px; border-radius: 5px; cursor: pointer;">Cerrar sesión</button>
    </form>
</header>
    
    <div id="search-container">
        <form method="GET" action="menu.php" style="display: inline;">
            <input type="text" id="search-input" name="busqueda" placeholder="Buscar menú..." value="<?= htmlspecialchars(stripslashes($busqueda)) ?>">
            <button type="submit" id="search-button">Buscar</button>
        </form>
    </div>

    <div style="text-align: center; margin-bottom: 20px;">
        <label for="condicion">Filtrar por condición alimenticia:</label><br>
        <button class="filter-button <?= $filtro === '' ? 'selected' : '' ?>" onclick="window.location.href='menu.php'">Todas</button>
        <button class="filter-button <?= $filtro === 'celiaco' ? 'selected' : '' ?>" onclick="window.location.href='menu.php?condicion=celiaco'">Celiaco</button>
        <button class="filter-button <?= $filtro === 'vegano' ? 'selected' : '' ?>" onclick="window.location.href='menu.php?condicion=vegano'">Vegano</button>
        <button class="filter-button <?= $filtro === 'vegetariano' ? 'selected' : '' ?>" onclick="window.location.href='menu.php?condicion=vegetariano'">Vegetariano</button>
        <button class="filter-button <?= $filtro === 'sin condicion alimenticia' ? 'selected' : '' ?>" onclick="window.location.href='menu.php?condicion=sin condicion alimenticia'">Menú General</button>
    </div>

    <div class="container">
        <?php
        // Arrays para cada tipo de menú
        $menus = [
            'Sin Condición Alimenticia' => [],
            'Vegano' => [],
            'Vegetariano' => [],
            'Celiaco' => []
        ];

        // Clasificar los menús según la condición alimenticia
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $condicion = $row['condicion_alimenticia'];
                switch ($condicion) {
                    case 'sin condicion alimenticia':
                        $menus['Sin Condición Alimenticia'][] = $row;
                        break;
                    case 'vegano':
                        $menus['Vegano'][] = $row;
                        break;
                    case 'vegetariano':
                        $menus['Vegetariano'][] = $row;
                        break;
                    case 'celiaco':
                        $menus['Celiaco'][] = $row;
                        break;
                }
            }
        }

// Mostrar los menús separados por condición alimenticia
foreach ($menus as $tipo => $items) {
    if (count($items) > 0) {
        echo "<div class='menu-section'><h2>$tipo</h2><div class='menu-items'>";
        foreach ($items as $item) {
            $nombre = $item['nombre'];
            $descripcion = $item['descripcion'];
            $precio = $item['precio'];
            $idmenu = $item['idmenu'];
            $imagen_url = $item['imagen_url']; // Obtener la URL de la imagen desde la base de datos
            // Calcular el descuento si hay puntos
            $descuento = floor($puntos); // Cada punto baja 1 peso
            $precio_final = max($precio - $descuento, 0); // Asegúrate de que el precio no sea negativo

            echo "<div class='menu-item'>";
            echo "<img src='$imagen_url' alt='$nombre' style='width: 50%; height: auto; border-radius: 5px; margin-bottom: 10px;max-width: 150px;'>"; // Mostrar imagen
            echo "<h3>$nombre</h3>";
            echo "<p>Descripción: $descripcion</p>";
            echo "<p>Precio: $$precio_final</p>"; // Muestra el precio con descuento
            echo "<form method='POST' action='agregar_al_carrito.php'>";
            echo "<input type='hidden' name='idmenu' value='$idmenu'>";
            
            // Contador de cantidad con botones
            echo "<div class='quantity-control'>";
            echo "<button type='button' class='btn-decrement' onclick='decrementQuantity($idmenu)'>-</button>";
            echo "<span id='quantity_$idmenu' class='quantity-value'>1</span>";
            echo "<button type='button' class='btn-increment' onclick='incrementQuantity($idmenu)'>+</button>";
            echo "</div>";
            
            echo "<input type='hidden' name='cantidad' id='hidden_quantity_$idmenu' value='1'>"; // Para enviar al carrito
            echo "<button type='submit'>Agregar al Carrito</button>";
            echo "</form>";
            echo "</div>";
        }
        echo "</div></div>";
    }
}

        ?>
    </div>

    <div id="cart-button">
        <a href="carrito.php"><button id="cart-toggle">🛒 (<span id="cart-count">0</span>)</button></a>
    </div>
    <footer>
    <div class="footer-content">
        <p>&copy; 2024 JAITEVA. Todos los derechos reservados. PROYECTO DE ALUMNOS DE 7mo 2da</p>
        <div class="social-media">
            <a href="https://www.facebook.com/EscuelaTecnicaLlano" target="_blank"><i class="fab fa-facebook-f"></i></a>
            <a href="https://www.instagram.com/jaitevacerveceria/" target="_blank"><i class="fab fa-instagram"></i></a>
        </div>
        <div class="location">
            <p>
                Ubicación: Puede Caificar su experiencia en el Local Aquí:
                <a href="https://www.google.com/maps/place/Escuela+Tecnica+Carmen+M.De+Llano/@-27.4650249,-58.8383206,19z/data=!3m1!4b1!4m6!3m5!1s0x94456de5c14bf0f1:0x49791b1948fad214!8m2!3d-27.4650261!4d-58.8376769!16s%2Fg%2F11cnbvt79m?entry=ttu&g_ep=EgoyMDI0MTAwMi4xIKXMDSoASAFQAw%3D%3D" target="_blank">
                    <i class="fas fa-map-marker-alt"></i>
                </a>
            </p>
        </div>
    </div>
</footer>
    <script>
        function updateCartCount() {
            const cartCountElement = document.getElementById('cart-count');
            const cartButton = document.getElementById('cart-button');

            fetch('get_cart_count.php')
                .then(response => response.json())
                .then(data => {
                    cartCountElement.textContent = data.count;
                    cartButton.style.display = data.count > 0 ? 'block' : 'none';
                });
        }

        document.addEventListener('DOMContentLoaded', updateCartCount);
        
        function incrementQuantity(idmenu) {
            var quantityElement = document.getElementById('quantity_' + idmenu);
            var hiddenQuantityElement = document.getElementById('hidden_quantity_' + idmenu);
            var currentQuantity = parseInt(quantityElement.innerText);
            quantityElement.innerText = currentQuantity + 1;
            hiddenQuantityElement.value = currentQuantity + 1; // Actualiza el valor oculto
        }

        function decrementQuantity(idmenu) {
            var quantityElement = document.getElementById('quantity_' + idmenu);
            var hiddenQuantityElement = document.getElementById('hidden_quantity_' + idmenu);
            var currentQuantity = parseInt(quantityElement.innerText);
            if (currentQuantity > 1) {
                quantityElement.innerText = currentQuantity - 1;
                hiddenQuantityElement.value = currentQuantity - 1; // Actualiza el valor oculto
            }
        }
    </script>
</body>
</html>
