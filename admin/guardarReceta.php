<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

require_once "../conexion.php";

$titulo = trim($_POST["titulo"] ?? "");
$ingredientes = trim($_POST["ingredientes"] ?? "");
$preparacion = trim($_POST["preparacion"] ?? "");

if (
    $titulo === "" ||
    $ingredientes === "" ||
    $preparacion === "" ||
    !isset($_FILES["imagen"]) ||
    $_FILES["imagen"]["error"] !== UPLOAD_ERR_OK ||
    $_FILES["imagen"]["size"] > 5 * 1024 * 1024
) {
    http_response_code(400);
    exit("Completá los campos y elegí una imagen de hasta 5 MB.");
}

$archivo = $_FILES["imagen"];
$tiposPermitidos = [
    "image/jpeg" => "jpg",
    "image/png" => "png",
    "image/webp" => "webp",
    "image/gif" => "gif",
];
$mime = (new finfo(FILEINFO_MIME_TYPE))->file($archivo["tmp_name"]);

if (!isset($tiposPermitidos[$mime])) {
    http_response_code(400);
    exit("La imagen debe ser JPG, PNG, WEBP o GIF.");
}

$directorio = "../img/recetas";
if (!is_dir($directorio) && !mkdir($directorio, 0755, true) && !is_dir($directorio)) {
    throw new RuntimeException("No se pudo preparar la carpeta de imágenes.");
}

$imagen = bin2hex(random_bytes(16)) . "." . $tiposPermitidos[$mime];
if (!move_uploaded_file($archivo["tmp_name"], $directorio . "/" . $imagen)) {
    throw new RuntimeException("No se pudo guardar la imagen subida.");
}

$consulta = $conexion->prepare("
    INSERT INTO recetas (titulo, ingredientes, preparacion, imagen)
    VALUES (?, ?, ?, ?)
");
$consulta->bind_param("ssss", $titulo, $ingredientes, $preparacion, $imagen);

if ($consulta->execute()) {
    header("Location: index.php?tab=recetas&creado=1");
    exit();
}

http_response_code(500);
echo "Error al guardar la receta.";
