<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Receta</title>
</head>
<body>

<h1>Agregar Receta</h1>

<form action="guardarReceta.php" method="POST">

    <label>Título</label><br>
    <input type="text" name="titulo" required><br><br>

    <label>Ingredientes</label><br>
    <textarea name="ingredientes" rows="6" cols="50" required></textarea><br><br>

    <label>Preparación</label><br>
    <textarea name="preparacion" rows="8" cols="50" required></textarea><br><br>

    <label>Imagen</label><br>
    <input type="text" name="imagen" placeholder="ej: receta.jpg" required><br><br>

    <button type="submit">Guardar Receta</button>

</form>

<br>

<a href="recetas.php">← Volver</a>

</body>
</html>