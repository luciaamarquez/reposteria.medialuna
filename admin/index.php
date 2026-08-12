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
    <title>Panel de Administración</title>
</head>
<body>

    <h1>Panel de Administración</h1>

    <p>Bienvenido, <?php echo $_SESSION["nombre"]; ?>.</p>

    <hr>

    <ul>
        <li><a href="productos.php">Gestionar productos</a></li>
        <li><a href="recetas.php">Gestionar recetas</a></li>
        <li><a href="../index.php">Ir a la tienda</a></li>
        <li><a href="../logout.php">Cerrar sesión</a></li>
    </ul>

</body>
</html>