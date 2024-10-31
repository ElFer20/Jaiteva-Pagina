<?php
// Configuración de la conexión a la base de datos
$servername = "localhost"; // Nombre del host de MySQL
$username = "u345853623_proyecto"; // Tu usuario de MySQL
$password = "y&Qpax+g:I0"; // Tu contraseña de MySQL
$dbname = "u345853623_jaiteva_final"; // Nombre de la base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
