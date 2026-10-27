<?php
session_start();

    if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
        header("Location: ../login.php");
        exit();
    }

    require_once "../conexion.php";

    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    $nombre = trim($_POST["nombre"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $precioEntrada = $_POST["precio"] ?? "";
    $stockEntrada = $_POST["stock"] ?? "";
    $precio = is_numeric($precioEntrada) ? (float) $precioEntrada : false;
    $stock = filter_var($stockEntrada, FILTER_VALIDATE_INT);
    $imagen = basename($_POST["imagen_actual"] ?? "");
    $id_categoria = filter_input(INPUT_POST, "id_categoria", FILTER_VALIDATE_INT);

    if (!$id || $nombre === "" || $descripcion === "" || $precio === false || $precio <= 0 ||
        $stock === false || $stock === null || $stock < 0 || $stock > 30 || !$id_categoria) {
        http_response_code(400);
        exit("Revisá los datos: el precio debe ser positivo y el stock debe estar entre 0 y 30.");
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
            exit("El archivo debe ser JPG, PNG, WEBP o GIF.");
        }

        $directorio = "../img/productos";
        if (!is_dir($directorio) && !mkdir($directorio, 0755, true) && !is_dir($directorio)) {
            throw new RuntimeException("No se pudo preparar la carpeta de imágenes.");
        }

        $imagen = bin2hex(random_bytes(16)) . "." . $tiposPermitidos[$mime];
        if (!move_uploaded_file($archivo["tmp_name"], $directorio . "/" . $imagen)) {
            throw new RuntimeException("No se pudo guardar la imagen subida.");
        }
    }

    if ($imagen === "") {
        http_response_code(400);
        exit("Seleccioná una imagen para el producto.");
    }

    $consulta = $conexion->prepare("
        UPDATE productos
        SET nombre = ?, descripcion = ?, precio = ?, stock = ?, imagen = ?, id_categoria = ?
        WHERE id = ?
    ");

    $consulta->bind_param(
        "",
        $nombre,
        $descripcion,
        $precio,
        $stock,
        $imagen,
        $id_categoria,
        $id
    );

    if ($consulta->execute()) {
        header("Location: index.php?tab=productos&actualizado=1");
        exit();
    } else {
        echo "Error al actualizar el producto.";
    }