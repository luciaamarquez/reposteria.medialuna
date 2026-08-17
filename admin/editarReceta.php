<?php
session_start();
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../login.php");
    exit();
}
require_once "../conexion.php";

$id = $_GET["id"];

$consulta = $conexion->prepare("SELECT * FROM recetas WHERE id = ?");
$consulta->bind_param("i", $id);
$consulta->execute();

$resultado = $consulta->get_result();
$receta = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Editar Receta</title>
    </head>
    <body>
    <h1>Editar Receta</h1>
        <form action="actualizarReceta.php" method="POST">

            <input type="hidden" name="id" value="<?php echo $receta["id"]; ?>">

            <label>Título</label><br>
            <input type="text" name="titulo" value="<?php echo $receta["titulo"]; ?>" required><br><br>

            <label>Ingredientes</label><br>
            <textarea name="ingredientes" rows="6" cols="50" required><?php echo $receta["ingredientes"]; ?></textarea><br><br>

            <label>Preparación</label><br>
            <textarea name="preparacion" rows="8" cols="50" required><?php echo $receta["preparacion"]; ?></textarea><br><br>

            <label>Imagen</label><br>
            <input type="text" name="imagen" value="<?php echo $receta["imagen"]; ?>" required><br><br>

            <button type="submit">Actualizar Receta</button>

        </form> <br>
        <a href="recetas.php">← Volver</a>
    </body>
</html>