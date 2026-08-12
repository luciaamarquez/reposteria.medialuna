<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once "../conexion.php";

$resultado = $conexion->query("SELECT * FROM recetas");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Recetas</title>
</head>
<body>

<h1>Administración de Recetas</h1>

<p>Bienvenido, <?php echo $_SESSION["nombre"]; ?></p>

<a href="agregarReceta.php">➕ Agregar receta</a>

<br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Imagen</th>
        <th>Acciones</th>
    </tr>

    <?php while($receta = $resultado->fetch_assoc()) { ?>

    <tr>
        <td><?php echo $receta["id"]; ?></td>
        <td><?php echo $receta["titulo"]; ?></td>
        <td><?php echo $receta["imagen"]; ?></td>
        <td>
            <a href="editarReceta.php?id=<?php echo $receta["id"]; ?>">Editar</a> |
            <a href="eliminarReceta.php?id=<?php echo $receta["id"]; ?>"
               onclick="return confirm('¿Estás seguro de eliminar esta receta?');">
                Eliminar
            </a>
        </td>
    </tr>

    <?php } ?>

</table>

</body>
</html>