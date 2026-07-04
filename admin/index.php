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

    <h1>Bienvenido al Panel de Administración</h1>

    <p>Hola, <?php echo $_SESSION["nombre"]; ?>.</p>

</body>
</html>