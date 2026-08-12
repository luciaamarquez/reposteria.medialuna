<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once "../conexion.php";

$id = $_POST["id"];
$titulo = $_POST["titulo"];
$ingredientes = $_POST["ingredientes"];
$preparacion = $_POST["preparacion"];
$imagen = $_POST["imagen"];

$consulta = $conexion->prepare("
UPDATE recetas
SET titulo = ?, ingredientes = ?, preparacion = ?, imagen = ?
WHERE id = ?
");

$consulta->bind_param(
    "ssssi",
    $titulo,
    $ingredientes,
    $preparacion,
    $imagen,
    $id
);

if ($consulta->execute()) {
    header("Location: recetas.php");
    exit();
} else {
    echo "Error al actualizar la receta.";
}