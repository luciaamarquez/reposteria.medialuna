<?php
session_start();
require_once "conexion.php";

// Verificar que haya un usuario logueado
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION["id"];

// Buscar los datos del usuario
$consulta = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
$consulta->bind_param("i", $id);
$consulta->execute();

$resultado = $consulta->get_result();

if ($resultado->num_rows != 1) {
    echo "Usuario no encontrado.";
    exit();
}

$usuario = $resultado->fetch_assoc();
$avisoPerfil = "";
$tipoAvisoPerfil = "";

if (($_GET["password"] ?? "") === "actualizada") {
    $avisoPerfil = "Contraseña actualizada.";
    $tipoAvisoPerfil = "exito";
} elseif (isset($_GET["mensaje"])) {
    $avisoPerfil = (string) $_GET["mensaje"];
    $tipoAvisoPerfil = "exito";
} elseif (isset($_GET["error"])) {
    $avisoPerfil = (string) $_GET["error"];
    $tipoAvisoPerfil = "error";
}

$esAdmin = isset($_SESSION["rol"]) && $_SESSION["rol"] === "admin";

if ($esAdmin) {
    $historialConsulta = $conexion->prepare("
        SELECT pedidos.id, pedidos.id_usuario, pedidos.fecha, pedidos.total,
               usuarios.nombre, usuarios.apellido, usuarios.email,
               detalle_pedidos.cantidad, detalle_pedidos.precio,
               productos.nombre AS producto
        FROM pedidos
        INNER JOIN usuarios ON usuarios.id = pedidos.id_usuario
        LEFT JOIN detalle_pedidos ON detalle_pedidos.id_pedido = pedidos.id
        LEFT JOIN productos ON productos.id = detalle_pedidos.id_producto
        ORDER BY pedidos.fecha DESC, pedidos.id DESC
    ");
    $historialConsulta->execute();
} else {
    $historialConsulta = $conexion->prepare("
        SELECT pedidos.id, pedidos.id_usuario, pedidos.fecha, pedidos.total,
               usuarios.nombre, usuarios.apellido, usuarios.email,
               detalle_pedidos.cantidad, detalle_pedidos.precio,
               productos.nombre AS producto
        FROM pedidos
        INNER JOIN usuarios ON usuarios.id = pedidos.id_usuario
        LEFT JOIN detalle_pedidos ON detalle_pedidos.id_pedido = pedidos.id
        LEFT JOIN productos ON productos.id = detalle_pedidos.id_producto
        WHERE pedidos.id_usuario = ?
        ORDER BY pedidos.fecha DESC, pedidos.id DESC
    ");
    $historialConsulta->bind_param("i", $id);
    $historialConsulta->execute();
}

$pedidos = [];
$resultadoHistorial = $historialConsulta->get_result();

while ($fila = $resultadoHistorial->fetch_assoc()) {
    $idPedido = (int) $fila["id"];

    if (!isset($pedidos[$idPedido])) {
        $pedidos[$idPedido] = [
            "id" => $idPedido,
            "fecha" => $fila["fecha"],
            "total" => $fila["total"],
            "estado" => $fila["estado"],
            "nombre" => $fila["nombre"],
            "apellido" => $fila["apellido"],
            "email" => $fila["email"],
            "items" => [],
        ];
    }

    if ($fila["producto"] !== null) {
        $pedidos[$idPedido]["items"][] = [
            "producto" => $fila["producto"],
            "cantidad" => $fila["cantidad"],
            "precio" => $fila["precio"],
        ];
    }
}

include("includes/header.php");
?>

<main class="perfil">

    <div class="perfil-titulo">
        <h1>Mi Perfil</h1>
        <p>Administrá la información de tu cuenta.</p>
    </div>

    <?php if ($avisoPerfil !== ""): ?>
        <p class="perfil-aviso perfil-notificacion <?= htmlspecialchars($tipoAvisoPerfil) ?>" role="status">
            <?= htmlspecialchars($avisoPerfil) ?>
        </p>
    <?php endif; ?>

    <div class="perfil-layout">

        <!-- CARD IZQUIERDA -->
        <aside class="card-usuario">

            <div class="perfil-icono">
                <i class="fa-solid fa-user"></i>
            </div>

            <h2 id="perfil-nombre">
                <?php echo htmlspecialchars($usuario["nombre"]); ?>
                <?php echo htmlspecialchars($usuario["apellido"]); ?>
            </h2>

            <span class="perfil-rol">
                <?php echo ucfirst($usuario["rol"]); ?>
            </span>

            <div class="info-usuario">

                <p>
                    <i class="fa-solid fa-envelope"></i>
                    <span id="perfil-email"><?php echo htmlspecialchars($usuario["email"]); ?></span>
                </p>

                <p>
                    <i class="fa-solid fa-phone"></i>
                    <span id="perfil-telefono"><?php echo htmlspecialchars($usuario["telefono"]); ?></span>
                </p>

                <p>
                    <i class="fa-solid fa-location-dot"></i>
                    <span id="perfil-direccion"><?php echo htmlspecialchars($usuario["direccion"]); ?></span>
                </p>

            </div>

            <a href="logout.php" class="btn-salir">
                <i class="fa-solid fa-right-from-bracket"></i>
                Cerrar sesión
            </a>

            <a href="#historial" class="btn-salir">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <?= $esAdmin ? "Historial de todos los pedidos" : "Historial de compras" ?>
            </a>

        </aside>


        <section>

            <div class="card-editar">

                <form action="actualizar_perfil.php" method="POST" id="form-perfil">

                    <h3>
                        <i class="fa-solid fa-pen"></i>
                        Información personal
                    </h3>

                    <div class="fila-form">

                        <div class="campo">
                            <label>Nombre</label>
                            <input type="text" name="nombre"
                                value="<?php echo htmlspecialchars($usuario["nombre"]); ?>">
                        </div>

                        <div class="campo">
                            <label>Apellido</label>
                            <input type="text" name="apellido"
                                value="<?php echo htmlspecialchars($usuario["apellido"]); ?>">
                        </div>

                    </div>

                    <div class="campo">
                        <label>Email</label>
                        <input type="email" name="email"
                            value="<?php echo htmlspecialchars($usuario["email"]); ?>">
                    </div>

                    <div class="fila-form">

                        <div class="campo">
                            <label>Teléfono</label>
                            <input type="text" name="telefono"
                                value="<?php echo htmlspecialchars($usuario["telefono"]); ?>">
                        </div>

                        <div class="campo">
                            <label>Dirección</label>
                            <input type="text" name="direccion"
                                value="<?php echo htmlspecialchars($usuario["direccion"]); ?>">
                        </div>

                    </div>

                    <h3 class="perfil-seccion-titulo">
                        <i class="fa-solid fa-lock"></i>
                        Seguridad
                    </h3>

                    <p class="ayuda-password">
                        Dejá estos campos vacíos si no querés cambiar tu contraseña.
                    </p>

                    <div class="fila-form">

                        <div class="campo">
                            <label>Nueva contraseña</label>
                            <input type="password" name="password">
                        </div>

                        <div class="campo">
                            <label>Confirmar contraseña</label>
                            <input type="password" name="confirmar">
                        </div>
                    </div>

                    <div class="perfil-botones">

                        <a href="index.php" class="btn-volver">
                            <i class="fa-solid fa-house"></i>
                            Inicio
                        </a>

                        <button type="submit" class="btn-guardar">
                            <i class="fa-solid fa-floppy-disk"></i>
                            GUARDAR CAMBIOS
                        </button>
                    </div>

                </form>

            </div>

        </section>

    </div>

    <section class="historial-perfil" id="historial">
        <div class="perfil-titulo">
            <h2><?= $esAdmin ? "Historial de todos los usuarios" : "Mis compras" ?></h2>
            <?php if ($esAdmin): ?>
                <p>Consultá los pedidos de todos los clientes.</p>
            <?php else: ?>
                <p>Revisá tus pedidos y el estado de cada compra.</p>
            <?php endif; ?>
        </div>

        <?php if (($_GET["pedido"] ?? "") === "confirmado"): ?>
            <p class="pedido-confirmado" role="status">Tu pedido se registró correctamente.</p>
        <?php endif; ?>

        <?php if (count($pedidos) === 0): ?>
            <article class="historial-pedido">
                <p>Todavía no hay compras registradas.</p>
                <a href="productos.php">Explorar productos</a>
            </article>
        <?php else: ?>
            <div class="historial-lista">
                <?php foreach ($pedidos as $pedido): ?>
                    <article class="historial-pedido">
                        <header>
                            <div>
                                <h3>Pedido #<?= $pedido["id"] ?></h3>
                                <p><?= htmlspecialchars($pedido["fecha"]) ?></p>
                                <?php if ($esAdmin): ?>
                                    <p>
                                        <?= htmlspecialchars($pedido["nombre"] . " " . $pedido["apellido"]) ?>
                                        · <?= htmlspecialchars($pedido["email"]) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                                <span class="historial-estado">
                                    Pedido recibido
                                </span>
                        </header>

                        <ul>
                            <?php foreach ($pedido["items"] as $item): ?>
                                <li>
                                    <?= htmlspecialchars($item["producto"]) ?>
                                    × <?= (int) $item["cantidad"] ?>
                                    — $<?= number_format((float) $item["precio"] * (int) $item["cantidad"], 2, ",", ".") ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <p class="historial-total">
                            Total: $<?= number_format((float) $pedido["total"], 2, ",", ".") ?>
                        </p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

</main>

<?php include("includes/footer.php"); ?>