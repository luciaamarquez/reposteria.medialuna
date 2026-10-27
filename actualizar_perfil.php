<?php
session_start();
require_once "conexion.php";

// Verificar que haya un usuario logueado
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: perfil.php");
    exit();
}

$id = $_SESSION["id"];
$nombre = trim($_POST["nombre"]);
$apellido = trim($_POST["apellido"]);
$email = trim($_POST["email"]);
$telefono = trim($_POST["telefono"]);
$direccion = trim($_POST["direccion"]);
$password = $_POST["password"];
$confirmar = $_POST["confirmar"];

// VALIDAR CAMPOS
if ($nombre == "" || $apellido == "" || $email == "") {
    header("Location: perfil.php?error=Completá todos los campos obligatorios.");
    exit();
}

// VALIDAR EMAIL
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: perfil.php?error=El email no es válido.");
    exit();
}

// COMPROBAR QUE EL EMAIL NO ESTÉ USADO POR OTRO USUARIO
$consulta = $conexion->prepare(
    "SELECT id FROM usuarios WHERE email = ? AND id != ?"
);

$consulta->bind_param("si", $email, $id);
$consulta->execute();
$resultado = $consulta->get_result();

if ($resultado->num_rows > 0) {
    header("Location: perfil.php?error=Ese email ya está siendo utilizado.");
    exit();
}

// SI QUIERE CAMBIAR LA CONTRASEÑA
if ($password != "" || $confirmar != "") {

// Comprobar que coincidan
    if ($password != $confirmar) {
        header("Location: perfil.php?error=Las contraseñas no coinciden.");
        exit();
    }

// Comprobar longitud
    if (strlen($password) < 6) {
        header("Location: perfil.php?error=La contraseña debe tener al menos 6 caracteres.");
        exit();
    }

// Encriptar contraseña
    $passwordHash = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

// Actualizar TODO incluyendo contraseña
    $consulta = $conexion->prepare(
        "UPDATE usuarios 
        SET nombre = ?, 
            apellido = ?, 
            email = ?, 
            telefono = ?, 
            direccion = ?, 
            password = ?
        WHERE id = ?"
    );

    $consulta->bind_param(
        "ssssssi",
        $nombre,
        $apellido,
        $email,
        $telefono,
        $direccion,
        $passwordHash,
        $id
    );
} else {

// Actualizar sin cambiar contraseña
    $consulta = $conexion->prepare(
        "UPDATE usuarios 
        SET nombre = ?, 
            apellido = ?, 
            email = ?, 
            telefono = ?, 
            direccion = ?
        WHERE id = ?"
    );

    $consulta->bind_param(
        "sssssi",
        $nombre,
        $apellido,
        $email,
        $telefono,
        $direccion,
        $id
    );
}
// EJECUTAR

if ($consulta->execute()) {

// Actualizar nombre que aparece en la sesión

$_SESSION["nombre"] = $nombre;
    header(
        "Location: perfil.php?mensaje=¡Tus datos fueron actualizados correctamente!"
    );
    exit();
} else {
    header(
        "Location: perfil.php?error=Ocurrió un error al actualizar tus datos."
    );
    exit();
}