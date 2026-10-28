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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Editar Receta</title>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    </head>
    <body>
    <div class="editar-receta">
    <div class="editar-receta-card">

        <h1>Editar Receta</h1>

        <form action="actualizarReceta.php" method="POST" enctype="multipart/form-data">

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
    <label>Imagen actual</label>
    <input type="text" value="<?php echo htmlspecialchars($receta["imagen"]); ?>" disabled>
    <input type="hidden" name="imagen_actual" value="<?php echo htmlspecialchars($receta["imagen"]); ?>">
    <label for="imagen">Elegir otra imagen (opcional)</label>
    <input type="file" id="imagen" name="imagen" accept="image/jpeg,image/png,image/webp,image/gif">
</div>
            <div class="editar-botones">
                <a href="index.php" class="btn-volver-editar">CANCELAR</a>
                <button type="submit" class="btn-actualizar">ACTUALIZAR</button>
            </div>

        </form>

    </div>
</div>
</body>
</html>

<?php
include("../includes/footer.php");
?>