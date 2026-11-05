<?php
session_start();
require_once "conexion.php";

function responderActualizacionPerfil(string $tipo, string $mensaje, array $datos = []): void
{
    $esAjax = strtolower($_SERVER["HTTP_X_REQUESTED_WITH"] ?? "") === "xmlhttprequest";

    if ($esAjax) {
        http_response_code($tipo === "error" ? 422 : 200);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(
            ["ok" => $tipo !== "error", "tipo" => $tipo, "mensaje" => $mensaje] + $datos,
            JSON_UNESCAPED_UNICODE
        );
        exit();
    }

    $parametro = $tipo === "error" ? "error" : "mensaje";
    header("Location: perfil.php?" . $parametro . "=" . urlencode($mensaje));
    exit();
}

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
$nombre = trim($_POST["nombre"] ?? "");
$apellido = trim($_POST["apellido"] ?? "");
$email = trim($_POST["email"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$direccion = trim($_POST["direccion"] ?? "");
$password = $_POST["password"] ?? "";
$confirmar = $_POST["confirmar"] ?? "";

// VALIDAR CAMPOS
if ($nombre == "" || $apellido == "" || $email == "") {
    responderActualizacionPerfil("error", "Completá todos los campos obligatorios.");
}

// VALIDAR EMAIL
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    responderActualizacionPerfil("error", "El email no es válido.");
}

// COMPROBAR QUE EL EMAIL NO ESTÉ USADO POR OTRO USUARIO
$consulta = $conexion->prepare(
    "SELECT id FROM usuarios WHERE email = ? AND id != ?"
);

$consulta->bind_param("si", $email, $id);
$consulta->execute();
$resultado = $consulta->get_result();

if ($resultado->num_rows > 0) {
    responderActualizacionPerfil("error", "Ese email ya está siendo utilizado.");
}

// SI QUIERE CAMBIAR LA CONTRASEÑA
if ($password != "" || $confirmar != "") {

// Comprobar que coincidan
    if ($password != $confirmar) {
        responderActualizacionPerfil("error", "Las contraseñas no coinciden.");
    }

// Comprobar longitud
    if (strlen($password) < 6) {
        responderActualizacionPerfil("error", "La contraseña debe tener al menos 6 caracteres.");
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
    $mensaje = $password !== ""
        ? "Contraseña actualizada."
        : "¡Tus datos fueron actualizados correctamente!";
    responderActualizacionPerfil("exito", $mensaje, [
        "usuario" => [
            "nombre" => $nombre,
            "apellido" => $apellido,
            "email" => $email,
            "telefono" => $telefono,
            "direccion" => $direccion,
        ],
    ]);
} else {
    responderActualizacionPerfil("error", "Ocurrió un error al actualizar tus datos.");
}