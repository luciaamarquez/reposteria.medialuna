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

include("../includes/header.php");
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Editar Receta</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>
    <div class="editar-receta">
    <div class="editar-receta-card">

        <h1>Editar Receta</h1>

        <form action="actualizarReceta.php" method="POST">

            <input type="hidden" name="id" value="<?php echo $receta["id"]; ?>">

            <div class="editar-grupo">
    <label>Título</label>
    <input type="text" name="titulo"
        value="<?php echo $receta["titulo"]; ?>" required>
</div>

<div class="doble-textarea">

    <div class="editar-grupo">
        <label>Ingredientes</label>
        <textarea name="ingredientes" required><?php echo $receta["ingredientes"]; ?></textarea>
    </div>

    <div class="editar-grupo">
        <label>Preparación</label>
        <textarea name="preparacion" required><?php echo $receta["preparacion"]; ?></textarea>
    </div>

</div>

<div class="editar-grupo">
    <label>Imagen</label>
    <input type="text" name="imagen"
        value="<?php echo $receta["imagen"]; ?>" required>
</div>
            <div class="editar-botones">
                <a href="index.php" class="btn-volver-editar">Cancelar</a>
                <button type="submit" class="btn-actualizar">Actualizar</button>
            </div>

        </form>

    </div>
</div>
</body>
</html>

<?php
include("../includes/footer.php");
?>