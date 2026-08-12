<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once "../conexion.php";

$sql = "SELECT p.*, c.nombre AS categoria
        FROM productos p
        INNER JOIN categoria c
        ON p.id_categoria = c.id";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Productos</title>
</head>
<body>

<h1>Administración de Productos</h1>

<p>Bienvenido, <?php echo $_SESSION["nombre"]; ?></p>

<a href="agregarProducto.php">➕ Agregar producto</a>

<br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Precio</th>
        <th>Stock</th>
        <th>Categoría</th>
        <th>Acciones</th>
    </tr>

    <?php while($producto = $resultado->fetch_assoc()) { ?>

    <tr>
        <td><?php echo $producto["id"]; ?></td>
        <td><?php echo $producto["nombre"]; ?></td>
        <td>$<?php echo $producto["precio"]; ?></td>
        <td><?php echo $producto["stock"]; ?></td>
        <td><?php echo $producto["categoria"]; ?></td>
        <td>
            <a href="editarProducto.php?id=<?php echo $producto["id"]; ?>">Editar</a>
            <a href="eliminarProducto.php?id=<?php echo $producto["id"]; ?>"
            onclick="return confirm('¿Estás seguro de que querés eliminar este producto?');">
                Eliminar
            </a>       
         </td>
    </tr>

    <?php } ?>

</table>

</body>
</html>