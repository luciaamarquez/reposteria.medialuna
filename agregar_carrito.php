<?php
session_start();
require_once "conexion.php";

$esAjax = ($_SERVER["HTTP_ACCEPT"] ?? "") === "application/json";

$responder = static function (int $estado, string $mensaje) use ($esAjax): void {
    if ($esAjax) {
        http_response_code($estado);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(["message" => $mensaje], JSON_UNESCAPED_UNICODE);
        exit();
    }

    if ($estado === 401) {
        header("Location: login.php");
    } else {
        header("Location: productos.php");
    }
    exit();
};

if (!isset($_SESSION["id"])) {
    $responder(401, "Iniciá sesión para agregar productos al carrito.");
}

$token = $_POST["csrf_token"] ?? "";
if (
    !isset($_SESSION["csrf_token"]) ||
    !is_string($token) ||
    !hash_equals($_SESSION["csrf_token"], $token)
) {
    $responder(400, "La sesión venció. Actualizá la página e intentá nuevamente.");
}

$idUsuario = (int) $_SESSION["id"];
$idProducto = filter_input(INPUT_POST, "id_producto", FILTER_VALIDATE_INT);
$cantidadEntrada = filter_input(INPUT_POST, "cantidad", FILTER_VALIDATE_INT);
$cantidad = $cantidadEntrada === false || $cantidadEntrada === null ? 1 : $cantidadEntrada;

if (!$idProducto || $cantidad < 1 || $cantidad > 30) {
    $responder(400, "La cantidad solicitada no es válida.");
}

$conexion->begin_transaction();

try {
    $consulta = $conexion->prepare("SELECT stock FROM productos WHERE id = ? FOR UPDATE");
    $consulta->bind_param("i", $idProducto);
    $consulta->execute();
    $producto = $consulta->get_result()->fetch_assoc();

    if (!$producto) {
        throw new RuntimeException("No encontramos ese producto.");
    }

    $consultaCarrito = $conexion->prepare("
        SELECT id, cantidad FROM carrito
        WHERE id_usuario = ? AND id_producto = ?
        FOR UPDATE
    ");
    $consultaCarrito->bind_param("ii", $idUsuario, $idProducto);
    $consultaCarrito->execute();
    $item = $consultaCarrito->get_result()->fetch_assoc();

    $cantidadActual = $item ? (int) $item["cantidad"] : 0;
    $nuevaCantidad = $cantidadActual + $cantidad;

    if ((int) $producto["stock"] < 1 || $nuevaCantidad > (int) $producto["stock"]) {
        throw new RuntimeException("No hay stock suficiente para esa cantidad.");
    }

    if ($item) {
        $actualizar = $conexion->prepare("UPDATE carrito SET cantidad = ? WHERE id = ?");
        $actualizar->bind_param("ii", $nuevaCantidad, $item["id"]);
        $actualizar->execute();
    } else {
        $insertar = $conexion->prepare("
            INSERT INTO carrito (id_usuario, id_producto, cantidad)
            VALUES (?, ?, ?)
        ");
        $insertar->bind_param("iii", $idUsuario, $idProducto, $cantidad);
        $insertar->execute();
    }

    $conexion->commit();
    $responder(200, "Se agregó al carrito. Cantidad total: " . $nuevaCantidad . ".");
} catch (Throwable $e) {
    $conexion->rollback();
    $responder(400, $e->getMessage());
}
