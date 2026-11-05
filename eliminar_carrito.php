<?php

session_start();
require_once "conexion.php";

// Verificar que el usuario esté logueado
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

// Verificar que llegue el id del producto
if (!isset($_POST["id_producto"])) {
    header("Location: carrito.php");
    exit();
}

$id_usuario = $_SESSION["id"];
$id_producto = intval($_POST["id_producto"]);

// Eliminar el producto del carrito
$eliminar = $conexion->prepare("
    DELETE FROM carrito
    WHERE id_usuario = ?
    AND id_producto = ?
");

$eliminar->bind_param("ii", $id_usuario, $id_producto);
$eliminar->execute();

header("Location: carrito.php");
exit();