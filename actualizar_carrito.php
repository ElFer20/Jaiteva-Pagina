<?php
session_start();

// Verificar si se recibe el nombre y la cantidad
if (isset($_POST['nombre']) && isset($_POST['cantidad'])) {
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];

    // Asegurarse de que el carrito esté creado
    if (isset($_SESSION['carrito'])) {
        foreach ($_SESSION['carrito'] as &$item) {
            if ($item['nombre'] === $nombre) {
                $item['cantidad'] = $cantidad; // Actualizar la cantidad
                break;
            }
        }
    }
}
?>
