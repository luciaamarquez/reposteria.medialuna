<?php

session_start();
require_once "conexion.php";

// Verificar que el usuario esté logueado
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

// Verificar datos recibidos
if (!isset($_POST["id_producto"]) || !isset($_POST["accion"])) {
    header("Location: carrito.php");
    exit();
}

$id_usuario = $_SESSION["id"];
$id_producto = intval($_POST["id_producto"]);
$accion = $_POST["accion"];

// Obtener cantidad actual y stock
$consulta = $conexion->prepare("
    SELECT 
        carrito.id,
        carrito.cantidad,
        productos.stock
    FROM carrito
    INNER JOIN productos 
        ON carrito.id_producto = productos.id
    WHERE carrito.id_usuario = ?
    AND carrito.id_producto = ?
");

$consulta->bind_param("ii", $id_usuario, $id_producto);
$consulta->execute();

$resultado = $consulta->get_result();

if ($resultado->num_rows === 0) {
    header("Location: carrito.php");
    exit();
}

$item = $resultado->fetch_assoc();

$cantidad_actual = $item["cantidad"];
$stock = $item["stock"];

// Aumentar
if ($accion === "sumar") {

    if ($cantidad_actual < $stock) {

        $nueva_cantidad = $cantidad_actual + 1;

        $actualizar = $conexion->prepare("
            UPDATE carrito
            SET cantidad = ?
            WHERE id = ?
        ");

        $actualizar->bind_param(
            "ii",
            $nueva_cantidad,
            $item["id"]
        );

        $actualizar->execute();
    }
}

// Disminuir
elseif ($accion === "restar") {

    if ($cantidad_actual > 1) {

        $nueva_cantidad = $cantidad_actual - 1;

        $actualizar = $conexion->prepare("
            UPDATE carrito
            SET cantidad = ?
            WHERE id = ?
        ");

        $actualizar->bind_param(
            "ii",
            $nueva_cantidad,
            $item["id"]
        );

        $actualizar->execute();

    } else {

        // Si llega a 0, eliminamos el producto
        $eliminar = $conexion->prepare("
            DELETE FROM carrito
            WHERE id = ?
        ");

        $eliminar->bind_param("i", $item["id"]);
        $eliminar->execute();
    }
}

header("Location: carrito.php");
exit();