<?php
session_start();

require_once "../conexion.php";

$categorias = $conexion->query("SELECT id, nombre FROM categoria");
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
</head>
<body>

<h1>Agregar Producto</h1>

<form action="guardarProducto.php" method="POST">

    <label>Nombre</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Descripción</label><br>
    <textarea name="descripcion" required></textarea><br><br>

    <label>Precio</label><br>
    <input type="number" step="0.01" name="precio" required><br><br>

    <label>Stock</label><br>
    <input type="number" name="stock" required><br><br>

    <label>Imagen</label><br>
    <input type="text" name="imagen" placeholder="ej: torta.jpg" required><br><br>

    <label>Categoría</label><br>
<select name="id_categoria" required>
    <option value="">Seleccione una categoría</option>

    <?php while ($categoria = $categorias->fetch_assoc()) { ?>
        <option value="<?php echo $categoria["id"]; ?>">
            <?php echo $categoria["nombre"]; ?>
        </option>
    <?php } ?>

</select>

<br><br>

<br><br>

    <button type="submit">Guardar Producto</button>

</form>

<br>

<a href="productos.php">← Volver</a>

</body>
</html>