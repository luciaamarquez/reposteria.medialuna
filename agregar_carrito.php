<?php

session_start();
require_once "conexion.php";

// Verificar si el usuario está logueado
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

// Verificar que se recibió el producto
if (!isset($_POST["id_producto"])) {
    header("Location: productos.php");
    exit();
}

$id_usuario = $_SESSION["id"];
$id_producto = intval($_POST["id_producto"]);

// Buscar el producto
$consulta = $conexion->prepare(
    "SELECT id, stock FROM productos WHERE id = ?"
);

$consulta->bind_param("i", $id_producto);
$consulta->execute();

$resultado = $consulta->get_result();

if ($resultado->num_rows === 0) {
    header("Location: productos.php");
    exit();
}

$producto = $resultado->fetch_assoc();

// Verificar stock
if ($producto["stock"] <= 0) {
    header("Location: productos.php");
    exit();
}

// Verificar si el producto ya está en el carrito
$consulta_carrito = $conexion->prepare(
    "SELECT id, cantidad 
     FROM carrito 
     WHERE id_usuario = ? AND id_producto = ?"
);

$consulta_carrito->bind_param(
    "ii",
    $id_usuario,
    $id_producto
);

$consulta_carrito->execute();

$resultado_carrito = $consulta_carrito->get_result();

if ($resultado_carrito->num_rows > 0) {

    // Ya existe → aumentar cantidad
    $item = $resultado_carrito->fetch_assoc();

    $nueva_cantidad = $item["cantidad"] + 1;

    // No superar el stock
    if ($nueva_cantidad <= $producto["stock"]) {

        $actualizar = $conexion->prepare(
            "UPDATE carrito 
             SET cantidad = ? 
             WHERE id = ?"
        );

        $actualizar->bind_param(
            "ii",
            $nueva_cantidad,
            $item["id"]
        );

        $actualizar->execute();
    }

} else {

    // No existe → crear producto en carrito
    $cantidad = 1;

    $insertar = $conexion->prepare(
        "INSERT INTO carrito (id_usuario, id_producto, cantidad)
         VALUES (?, ?, ?)"
    );

    $insertar->bind_param(
        "iii",
        $id_usuario,
        $id_producto,
        $cantidad
    );

    $insertar->execute();
}

// Volver a productos
header("Location: productos.php");
exit();