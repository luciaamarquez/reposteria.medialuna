<?php
session_start();

    if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
        header("Location: ../login.php");
        exit();
    }

    require_once "../conexion.php";

    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    $titulo = trim($_POST["titulo"] ?? "");
    $ingredientes = trim($_POST["ingredientes"] ?? "");
    $preparacion = trim($_POST["preparacion"] ?? "");
    $imagen = basename($_POST["imagen_actual"] ?? "");

    if (!$id || $titulo === "" || $ingredientes === "" || $preparacion === "") {
        http_response_code(400);
        exit("Completá todos los campos de la receta.");
    }

    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $archivo = $_FILES["imagen"];
        $tiposPermitidos = [
            "image/jpeg" => "jpg",
            "image/png" => "png",
            "image/webp" => "webp",
            "image/gif" => "gif",
        ];

        if ($archivo["error"] !== UPLOAD_ERR_OK || $archivo["size"] > 5 * 1024 * 1024) {
            http_response_code(400);
            exit("La imagen no se pudo subir o supera el máximo de 5 MB.");
        }

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
    }

    $consulta = $conexion->prepare("
    UPDATE recetas
    SET titulo = ?, ingredientes = ?, preparacion = ?, imagen = ?
    WHERE id = ?
    ");

    $consulta->bind_param(
        "ssssi",
        $titulo,
        $ingredientes,
        $preparacion,
        $imagen,
        $id
    );

    if ($consulta->execute()) {
        header("Location: index.php?tab=recetas&actualizado=1");
        exit();
    } else {
        echo "Error al actualizar la receta.";
    }