<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once "../conexion.php";

$id = $_GET["id"];

// Obtener el producto
$consulta = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
$consulta->bind_param("i", $id);
$consulta->execute();
$resultado = $consulta->get_result();
$producto = $resultado->fetch_assoc();

// Obtener las categorías
$categorias = $conexion->query("SELECT id, nombre FROM categoria");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
</head>
<body>

<h1>Editar Producto</h1>

<form action="actualizarProducto.php" method="POST">

    <input type="hidden" name="id" value="<?php echo $producto["id"]; ?>">

    <label>Nombre</label><br>
    <input type="text" name="nombre" value="<?php echo $producto["nombre"]; ?>" required><br><br>

    <label>Descripción</label><br>
    <textarea name="descripcion" required><?php echo $producto["descripcion"]; ?></textarea><br><br>

    <label>Precio</label><br>
    <input type="number" step="0.01" name="precio" value="<?php echo $producto["precio"]; ?>" required><br><br>

    <label>Stock</label><br>
    <input type="number" name="stock" value="<?php echo $producto["stock"]; ?>" required><br><br>

    <label>Imagen</label><br>
    <input type="text" name="imagen" value="<?php echo $producto["imagen"]; ?>" required><br><br>

    <label>Categoría</label><br>
    <select name="id_categoria" required>
        <?php while ($categoria = $categorias->fetch_assoc()) { ?>
            <option value="<?php echo $categoria["id"]; ?>"
                <?php if ($categoria["id"] == $producto["id_categoria"]) echo "selected"; ?>>
                <?php echo $categoria["nombre"]; ?>
            </option>
        <?php } ?>
    </select>

    <br><br>

    <button type="submit">Actualizar Producto</button>

</form>

</body>
</html>