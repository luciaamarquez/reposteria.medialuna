<?php
session_start();
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../login.php");
    exit();
}
require_once "../conexion.php";

$titulo = $_POST["titulo"];
$ingredientes = $_POST["ingredientes"];
$preparacion = $_POST["preparacion"];
$imagen = $_POST["imagen"];

$consulta = $conexion->prepare("
    INSERT INTO recetas (titulo, ingredientes, preparacion, imagen)
    VALUES (?, ?, ?, ?)
");

$consulta->bind_param(
    "ssss",
    $titulo,
    $ingredientes,
    $preparacion,
    $imagen
);

if ($consulta->execute()) {
    header("Location: recetas.php");
    exit();
} else {
    echo "Error al guardar la receta.";
}