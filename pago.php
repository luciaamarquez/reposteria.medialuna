<?php
session_start();
require_once "conexion.php";

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

$idPedido = filter_input(INPUT_GET, "pedido", FILTER_VALIDATE_INT);
$resultadoPago = "";
$errorPago = "";
$correoEnviado = false;
$pagoSimulado = false;
$itemsPago = [];

if (!$idPedido) {
    http_response_code(404);
    exit("No se encontró la compra en curso.");
}

$cargarPedido = static function () use ($conexion, $idPedido, &$itemsPago): ?array {
    $consulta = $conexion->prepare("
        SELECT pedidos.id, pedidos.id_usuario, pedidos.fecha, pedidos.total, pedidos.estado,
               usuarios.nombre, usuarios.email, usuarios.telefono,
               usuarios.direccion
        FROM pedidos
        INNER JOIN usuarios ON usuarios.id = pedidos.id_usuario
        WHERE pedidos.id = ? AND pedidos.id_usuario = ?
    ");
    $idUsuario = (int) $_SESSION["id"];
    $consulta->bind_param("ii", $idPedido, $idUsuario);
    $consulta->execute();
    $pedidoActual = $consulta->get_result()->fetch_assoc();

    if (!$pedidoActual) {
        return null;
    }

    $consultaItems = $conexion->prepare("
        SELECT detalle_pedidos.cantidad, detalle_pedidos.precio, productos.nombre
        FROM detalle_pedidos
        INNER JOIN productos ON productos.id = detalle_pedidos.id_producto
        WHERE detalle_pedidos.id_pedido = ?
    ");
    $consultaItems->bind_param("i", $idPedido);
    $consultaItems->execute();
    $itemsPago = $consultaItems->get_result()->fetch_all(MYSQLI_ASSOC);
    return $pedidoActual;
};

$pedido = $cargarPedido();

if (!$pedido) {
    http_response_code(404);
    exit("No se encontró el pedido.");
}

if (
    (int) ($_SESSION["pedido_pago"] ?? 0) !== $idPedido &&
    $pedido["estado"] !== "pagado"
) {
    http_response_code(404);
    exit("No se encontró la compra en curso.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["csrf_token"] ?? "";
    if (
        !isset($_SESSION["csrf_token"]) ||
        !is_string($token) ||
        !hash_equals($_SESSION["csrf_token"], $token)
    ) {
        $errorPago = "La sesión del formulario venció. Volvé a intentarlo.";
    } else {
        $titular = trim($_POST["titular"] ?? "");
        $numeroTarjeta = preg_replace("/\D+/", "", $_POST["numero_tarjeta"] ?? "");
        $vencimiento = trim($_POST["vencimiento"] ?? "");
        $cvv = preg_replace("/\D+/", "", $_POST["cvv"] ?? "");

        if (
            $titular === "" ||
            strlen($numeroTarjeta) < 13 ||
            strlen($numeroTarjeta) > 19 ||
            !preg_match("/^(0[1-9]|1[0-2])\/\d{2}$/", $vencimiento) ||
            !preg_match("/^\d{3,4}$/", $cvv)
        ) {
            $errorPago = "Revisá el nombre, número, vencimiento y código de seguridad de la tarjeta.";
        } else {
            $partesVencimiento = explode("/", $vencimiento);
            $finDeVencimiento = DateTimeImmutable::createFromFormat(
                "!m/y",
                $partesVencimiento[0] . "/" . $partesVencimiento[1]
            );

            if (!$finDeVencimiento || $finDeVencimiento->modify("last day of this month") < new DateTimeImmutable("today")) {
                $errorPago = "La tarjeta está vencida.";
            } else {
                $transaccionAbierta = false;

                try {
                    $conexion->begin_transaction();
                    $transaccionAbierta = true;

                    $bloquearPedido = $conexion->prepare("
                        SELECT id, estado FROM pedidos
                        WHERE id = ? AND id_usuario = ?
                        FOR UPDATE
                    ");
                    $idUsuario = (int) $_SESSION["id"];
                    $bloquearPedido->bind_param("ii", $idPedido, $idUsuario);
                    $bloquearPedido->execute();
                    $pedidoBloqueado = $bloquearPedido->get_result()->fetch_assoc();

                    if (!$pedidoBloqueado || $pedidoBloqueado["estado"] !== "pendiente_pago") {
                        throw new RuntimeException("Este pedido ya fue procesado o no está disponible.");
                    }

                    $bloquearItems = $conexion->prepare("
                        SELECT detalle_pedidos.id_producto, detalle_pedidos.cantidad, productos.stock
                        FROM detalle_pedidos
                        INNER JOIN productos ON productos.id = detalle_pedidos.id_producto
                        WHERE detalle_pedidos.id_pedido = ?
                        FOR UPDATE
                    ");
                    $bloquearItems->bind_param("i", $idPedido);
                    $bloquearItems->execute();
                    $resultadoItems = $bloquearItems->get_result();
                    $itemsBloqueados = [];

                    while ($item = $resultadoItems->fetch_assoc()) {
                        if ((int) $item["cantidad"] > (int) $item["stock"]) {
                            throw new RuntimeException("No hay stock suficiente para completar este pedido.");
                        }
                        $itemsBloqueados[] = $item;
                    }

                    if (count($itemsBloqueados) === 0) {
                        throw new RuntimeException("El pedido no tiene productos.");
                    }

                    $descontarStock = $conexion->prepare("
                        UPDATE productos SET stock = stock - ?
                        WHERE id = ? AND stock >= ?
                    ");
                    foreach ($itemsBloqueados as $item) {
                        $descontarStock->bind_param(
                            "iii",
                            $item["cantidad"],
                            $item["id_producto"],
                            $item["cantidad"]
                        );
                        $descontarStock->execute();

                        if ($descontarStock->affected_rows !== 1) {
                            throw new RuntimeException("No se pudo actualizar el stock.");
                        }
                    }

                    $marcarPagado = $conexion->prepare("
                        UPDATE pedidos SET estado = 'pagado' WHERE id = ?
                    ");
                    $marcarPagado->bind_param("i", $idPedido);
                    $marcarPagado->execute();

                    $vaciarCarrito = $conexion->prepare("
                        DELETE FROM carrito WHERE id_usuario = ?
                    ");
                    $vaciarCarrito->bind_param("i", $idUsuario);
                    $vaciarCarrito->execute();

                    $conexion->commit();
                    $transaccionAbierta = false;
                    $pagoSimulado = true;
                    unset($_SESSION["pedido_pago"]);

                    $pedido = $cargarPedido();
                    $remitente = getenv("MAIL_FROM");

                    if ($remitente !== false && filter_var($remitente, FILTER_VALIDATE_EMAIL)) {
                        $lineasCorreo = [
                            "Hola " . $pedido["nombre"] . ",",
                            "",
                            "Tu compra fue registrada correctamente.",
                            "Pedido: #" . $pedido["id"],
                            "Fecha: " . $pedido["fecha"],
                            "",
                            "Detalle:",
                        ];

                        foreach ($itemsPago as $item) {
                            $lineasCorreo[] = sprintf(
                                "- %s x %d: $%s",
                                $item["nombre"],
                                $item["cantidad"],
                                number_format((float) $item["precio"] * (int) $item["cantidad"], 2, ",", ".")
                            );
                        }

                        $lineasCorreo[] = "";
                        $lineasCorreo[] = "Envío: $" . number_format(500, 2, ",", ".");
                        $lineasCorreo[] = "Total: $" . number_format((float) $pedido["total"], 2, ",", ".");
                        $lineasCorreo[] = "Dirección: " . $pedido["direccion"];

                        $asunto = "=?UTF-8?B?" . base64_encode("Compra Medialuna #" . $idPedido) . "?=";
                        $cabeceras = [
                            "From: Medialuna <" . $remitente . ">",
                            "MIME-Version: 1.0",
                            "Content-Type: text/plain; charset=UTF-8",
                        ];

                        if (mail($pedido["email"], $asunto, implode("\r\n", $lineasCorreo), implode("\r\n", $cabeceras))) {
                            $correoEnviado = true;
                        } else {
                            error_log("No se pudo enviar el correo del pedido " . $idPedido);
                        }
                    }
                } catch (Throwable $e) {
                    if ($transaccionAbierta) {
                        $conexion->rollback();
                    }
                    $errorPago = $e->getMessage();
                }
            }
        }
    }
}

include("includes/header.php");
?>

<main class="pago registro">
    <div class="pago-contenedor registro-contenedor">
        <?php if ($pagoSimulado): ?>
            <div class="pago-confirmacion">
                <i class="fa-solid fa-circle-check"></i>
                <h1>¡Compra confirmada!</h1>
                <p>Registramos el pedido #<?= (int) $idPedido ?> por $<?= number_format((float) $pedido["total"], 2, ",", ".") ?>.</p>
                <?php if ($correoEnviado): ?>
                    <p>Enviamos el detalle a <?= htmlspecialchars($pedido["email"]) ?>.</p>
                <?php else: ?>
                    <p>La compra quedó registrada. El correo de confirmación no está configurado en este entorno.</p>
                <?php endif; ?>
            </div>
        <?php elseif ($pedido["estado"] === "pagado"): ?>
            <div class="pago-confirmacion">
                <i class="fa-solid fa-circle-check"></i>
                <h1>Este pedido ya está confirmado</h1>
                <p>Pedido #<?= (int) $idPedido ?></p>
                <a class="btn-principal" href="perfil.php#historial">VER MI HISTORIAL</a>
            </div>
        <?php else: ?>
            <h1>Datos de pago</h1>
            <p class="pago-aviso">Simulación para el trabajo práctico: no se realiza un cobro real y los datos de tarjeta no se guardan.</p>

            <?php if ($errorPago !== ""): ?>
                <p class="mensaje-login-error" role="alert"><?= htmlspecialchars($errorPago) ?></p>
            <?php endif; ?>

            <div class="pago-resumen">
                <strong>Pedido #<?= (int) $idPedido ?></strong>
                <?php foreach ($itemsPago as $item): ?>
                    <p><?= htmlspecialchars($item["nombre"]) ?> × <?= (int) $item["cantidad"] ?></p>
                <?php endforeach; ?>
                <strong>Total: $<?= number_format((float) $pedido["total"], 2, ",", ".") ?></strong>
            </div>

            <form method="POST" action="pago.php?pedido=<?= (int) $idPedido ?>" class="formulario-pago" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"] ?? "") ?>">

                <label for="titular">Nombre del titular</label>
                <input type="text" id="titular" name="titular" autocomplete="cc-name" required>

                <label for="numero-tarjeta">Número de tarjeta</label>
                <input type="text" id="numero-tarjeta" name="numero_tarjeta" inputmode="numeric" autocomplete="cc-number" maxlength="23" placeholder="0000 0000 0000 0000" required>

                <div class="fila-form pago-campos-cortos">
                    <div>
                        <label for="vencimiento">Vencimiento</label>
                        <input type="text" id="vencimiento" name="vencimiento" inputmode="numeric" autocomplete="cc-exp" maxlength="5" placeholder="MM/AA" required>
                    </div>
                    <div>
                        <label for="cvv">Código de seguridad</label>
                        <input type="password" id="cvv" name="cvv" inputmode="numeric" autocomplete="cc-csc" maxlength="4" placeholder="CVV" required>
                    </div>
                </div>

                <button type="submit">CONFIRMAR COMPRA (SIMULACIÓN)</button>
            </form>
        <?php endif; ?>
    </div>
</main>

<script>
const numeroTarjeta = document.getElementById("numero-tarjeta");
if (numeroTarjeta) {
    numeroTarjeta.addEventListener("input", () => {
        const digitos = numeroTarjeta.value.replace(/\D/g, "").slice(0, 19);
        numeroTarjeta.value = digitos.replace(/(.{4})/g, "$1 ").trim();
    });
}

const vencimiento = document.getElementById("vencimiento");
if (vencimiento) {
    vencimiento.addEventListener("input", () => {
        const digitos = vencimiento.value.replace(/\D/g, "").slice(0, 4);
        vencimiento.value = digitos.length > 2
            ? digitos.slice(0, 2) + "/" + digitos.slice(2)
            : digitos;
    });
}
</script>

<?php include("includes/footer.php"); ?>
