<?php
session_start();

// Verificar si se recibe el nombre del plato
if (isset($_POST['nombre'])) {
    $nombre = $_POST['nombre'];

    // Asegurarse de que el carrito esté creado
    if (isset($_SESSION['carrito'])) {
        foreach ($_SESSION['carrito'] as $key => $item) {
            if ($item['nombre'] === $nombre) {
                unset($_SESSION['carrito'][$key]); // Eliminar el item
                break;
            }
        }
    }
}
?>
