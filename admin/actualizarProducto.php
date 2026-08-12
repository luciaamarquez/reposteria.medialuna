<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once "../conexion.php";

$id = $_POST["id"];
$nombre = $_POST["nombre"];
$descripcion = $_POST["descripcion"];
$precio = $_POST["precio"];
$stock = $_POST["stock"];
$imagen = $_POST["imagen"];
$id_categoria = $_POST["id_categoria"];

$consulta = $conexion->prepare("
    UPDATE productos
    SET nombre = ?, descripcion = ?, precio = ?, stock = ?, imagen = ?, id_categoria = ?
    WHERE id = ?
");

$consulta->bind_param(
    "ssdssii",
    $nombre,
    $descripcion,
    $precio,
    $stock,
    $imagen,
    $id_categoria,
    $id
);

if ($consulta->execute()) {
    header("Location: productos.php");
    exit();
} else {
    echo "Error al actualizar el producto.";
}