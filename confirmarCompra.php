<?php

session_start();
require_once "conexion.php";

// Verificar login
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION["id"];

// Iniciar transacción
$conexion->begin_transaction();

try {

    // Obtener productos del carrito
    $consulta = $conexion->prepare("
        SELECT
            carrito.id_producto,
            carrito.cantidad,
            productos.nombre,
            productos.precio,
            productos.stock
        FROM carrito
        INNER JOIN productos
            ON carrito.id_producto = productos.id
        WHERE carrito.id_usuario = ?
        FOR UPDATE
    ");

    $consulta->bind_param("i", $id_usuario);
    $consulta->execute();

    $resultado = $consulta->get_result();

    // Verificar que haya productos
    if ($resultado->num_rows === 0) {
        throw new Exception("El carrito está vacío.");
    }

    $productos = [];
    $total = 0;

    while ($producto = $resultado->fetch_assoc()) {

        // Verificar stock
        if ($producto["cantidad"] > $producto["stock"]) {
            throw new Exception(
                "No hay suficiente stock de " . $producto["nombre"]
            );
        }

        $subtotal = $producto["precio"] * $producto["cantidad"];

        $total += $subtotal;

        $productos[] = [
            "id_producto" => $producto["id_producto"],
            "cantidad" => $producto["cantidad"],
            "precio" => $producto["precio"],
            "stock" => $producto["stock"]
        ];
    }

    // Costo de envío
    $envio = 500;

    $total_final = $total + $envio;

    // Crear pedido
    $estado = "pendiente";
    $fecha = date("Y-m-d H:i:s");

    $pedido = $conexion->prepare("
        INSERT INTO pedidos
        (id_usuario, fecha, total, estado)
        VALUES (?, ?, ?, ?)
    ");

    $pedido->bind_param(
        "isds",
        $id_usuario,
        $fecha,
        $total_final,
        $estado
    );

    $pedido->execute();

    $id_pedido = $conexion->insert_id;

    // Insertar detalle de cada producto
    $detalle = $conexion->prepare("
        INSERT INTO detalle_pedidos
        (id_pedido, id_producto, cantidad, precio)
        VALUES (?, ?, ?, ?)
    ");

    // Actualizar stock
    $actualizar_stock = $conexion->prepare("
        UPDATE productos
        SET stock = stock - ?
        WHERE id = ?
    ");

    foreach ($productos as $producto) {

        $detalle->bind_param(
            "iiid",
            $id_pedido,
            $producto["id_producto"],
            $producto["cantidad"],
            $producto["precio"]
        );

        $detalle->execute();

        $actualizar_stock->bind_param(
            "ii",
            $producto["cantidad"],
            $producto["id_producto"]
        );

        $actualizar_stock->execute();
    }

    // Vaciar carrito
    $vaciar = $conexion->prepare("
        DELETE FROM carrito
        WHERE id_usuario = ?
    ");

    $vaciar->bind_param("i", $id_usuario);
    $vaciar->execute();

    // Confirmar transacción
    $conexion->commit();

    // Mostrar confirmación
    ?>

    <?php include("includes/header.php"); ?>

    <main class="compra-exitosa">

        <div class="compra-exitosa-contenedor">

            <i class="fa-solid fa-circle-check"></i>

            <h1>¡Compra realizada!</h1>

            <p>
                Tu pedido fue registrado correctamente.
            </p>

            <p>
                Número de pedido:
                <strong>#<?= $id_pedido ?></strong>
            </p>

            <p>
                Total:
                <strong>
                    $<?= number_format($total_final, 2, ',', '.') ?>
                </strong>
            </p>

            <a href="productos.php">
                SEGUIR COMPRANDO
            </a>

        </div>

    </main>

    <?php include("includes/footer.php"); ?>

    <?php

} catch (Exception $e) {

    // Si algo falla, deshacer todo
    $conexion->rollback();

    ?>

    <?php include("includes/header.php"); ?>

    <main class="compra-error">

        <div class="compra-error-contenedor">

            <i class="fa-solid fa-circle-exclamation"></i>

            <h1>No se pudo realizar la compra</h1>

            <p>
                <?= htmlspecialchars($e->getMessage()) ?>
            </p>

            <a href="carrito.php">
                VOLVER AL CARRITO
            </a>

        </div>

    </main>

    <?php include("includes/footer.php"); ?>

    <?php
}