<?php
session_start();
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../login.php");
    exit();
}
require_once "../conexion.php";

$nombre = $_POST["nombre"];
$descripcion = $_POST["descripcion"];
$precio = $_POST["precio"];
$stock = $_POST["stock"];
$imagen = $_POST["imagen"];
$id_categoria = $_POST["id_categoria"];

$consulta = $conexion->prepare(
    "INSERT INTO productos (nombre, descripcion, precio, stock, imagen, id_categoria)
    VALUES (?, ?, ?, ?, ?, ?)"
);

$consulta->bind_param(
    "ssdisi",
    $nombre,
    $descripcion,
    $precio,
    $stock,
    $imagen,
    $id_categoria
);

if ($consulta->execute()) {
    header("Location: productos.php");
    exit();
} else {
    echo "Error al guardar el producto.";
}