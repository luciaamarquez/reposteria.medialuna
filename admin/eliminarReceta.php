<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once "../conexion.php";

$id = $_GET["id"];

$consulta = $conexion->prepare("DELETE FROM recetas WHERE id = ?");
$consulta->bind_param("i", $id);

if ($consulta->execute()) {
    header("Location: recetas.php");
    exit();
} else {
    echo "Error al eliminar la receta.";
}