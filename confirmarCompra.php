<?php
session_start();
require_once "conexion.php";

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: carrito.php");
    exit();
}

$token = $_POST["csrf_token"] ?? "";
if (
    !isset($_SESSION["csrf_token"]) ||
    !is_string($token) ||
    !hash_equals($_SESSION["csrf_token"], $token)
) {
    http_response_code(400);
    exit("La sesión del formulario venció. Volvé al carrito e intentá nuevamente.");
}

$idUsuario = (int) $_SESSION["id"];
$envio = 500.00;
$transaccionAbierta = false;

try {
    $conexion->begin_transaction();
    $transaccionAbierta = true;

    $usuarioConsulta = $conexion->prepare("
        SELECT nombre, email FROM usuarios WHERE id = ?
    ");
    $usuarioConsulta->bind_param("i", $idUsuario);
    $usuarioConsulta->execute();
    $usuario = $usuarioConsulta->get_result()->fetch_assoc();

    if (!$usuario || !filter_var($usuario["email"], FILTER_VALIDATE_EMAIL)) {
        throw new RuntimeException("Agregá un correo válido a tu perfil antes de comprar.");
    }

    $consulta = $conexion->prepare("
        SELECT carrito.id_producto, carrito.cantidad, productos.nombre,
               productos.precio, productos.stock
        FROM carrito
        INNER JOIN productos ON productos.id = carrito.id_producto
        WHERE carrito.id_usuario = ?
        FOR UPDATE
    ");
    $consulta->bind_param("i", $idUsuario);
    $consulta->execute();
    $resultado = $consulta->get_result();
    $items = [];
    $subtotal = 0.0;

    while ($item = $resultado->fetch_assoc()) {
        if ((int) $item["cantidad"] < 1 || (int) $item["cantidad"] > (int) $item["stock"]) {
            throw new RuntimeException("No hay stock suficiente de " . $item["nombre"] . ".");
        }
        $subtotal += (float) $item["precio"] * (int) $item["cantidad"];
        $items[] = $item;
    }

    if (count($items) === 0) {
        throw new RuntimeException("El carrito está vacío.");
    }

    $total = $subtotal + $envio;
    $estado = "pendiente_pago";
    $fecha = date("Y-m-d H:i:s");
    $crearPedido = $conexion->prepare("
        INSERT INTO pedidos (id_usuario, fecha, total, estado)
        VALUES (?, ?, ?, ?)
    ");
    $crearPedido->bind_param("isds", $idUsuario, $fecha, $total, $estado);
    $crearPedido->execute();
    $idPedido = (int) $conexion->insert_id;

    $detalle = $conexion->prepare("
        INSERT INTO detalle_pedidos (id_pedido, id_producto, cantidad, precio)
        VALUES (?, ?, ?, ?)
    ");
    foreach ($items as $item) {
        $idProducto = (int) $item["id_producto"];
        $cantidad = (int) $item["cantidad"];
        $precio = (float) $item["precio"];
        $detalle->bind_param("iiid", $idPedido, $idProducto, $cantidad, $precio);
        $detalle->execute();
    }

    $conexion->commit();
    $transaccionAbierta = false;
    $_SESSION["pedido_pago"] = $idPedido;
    header("Location: pago.php?pedido=" . $idPedido);
    exit();
} catch (Throwable $e) {
    if ($transaccionAbierta) {
        $conexion->rollback();
    }
    $errorCompra = $e->getMessage();
}

include("includes/header.php");
?>

<main class="compra-error">
    <div class="compra-error-contenedor">
        <i class="fa-solid fa-circle-exclamation"></i>
        <h1>No se pudo preparar la compra</h1>
        <p><?= htmlspecialchars($errorCompra) ?></p>
        <a href="carrito.php">VOLVER AL CARRITO</a>
    </div>
</main>

<?php include("includes/footer.php"); ?>
