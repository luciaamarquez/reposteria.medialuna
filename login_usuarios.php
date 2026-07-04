<?php

session_start();
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Buscar usuario por email
    $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
    $consulta->bind_param("s", $email);
    $consulta->execute();

    $resultado = $consulta->get_result();

    if ($resultado->num_rows == 1) {

        $usuario = $resultado->fetch_assoc();

        // Verificar contraseña
        if (password_verify($password, $usuario["password"])) {

            $_SESSION["id"] = $usuario["id"];
            $_SESSION["nombre"] = $usuario["nombre"];
            $_SESSION["rol"] = $usuario["rol"];

            // Redirigir según el rol
            if ($usuario["rol"] == "admin") {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }

            exit();

        } else {
            echo "Contraseña incorrecta.";
        }

    } else {
        echo "El usuario no existe.";
    }
}