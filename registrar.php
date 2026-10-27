<?php

require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $email = trim($_POST["email"]);
    $telefono = trim($_POST["telefono"]);
    $direccion = trim($_POST["direccion"]);
    $password = $_POST["password"];
    $confirmar = $_POST["confirmar"];

    // Verificar que las contraseñas coincidan
    if ($password != $confirmar) {
        die("Las contraseñas no coinciden.");
    }

    // Verificar si el email ya existe
    $consulta = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
    $consulta->bind_param("s", $email);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows > 0) {
        die("El email ya está registrado.");
    }

    // Encriptar contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $rol = "cliente";

    // Insertar usuario
$insertar = $conexion->prepare("INSERT INTO usuarios (nombre, apellido, email, telefono, direccion, password, rol) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insertar->bind_param(
        "sssssss",
        $nombre,
        $apellido,
        $email,
        $telefono,
        $direccion,
        $passwordHash,
        $rol
    );

    if ($insertar->execute()) {
        header("Location: login.php");
        exit();
    } else {
        echo "Error al registrar el usuario.";
    }
}