<?php

session_start();
require_once "conexion.php";

// Verificar si el usuario inició sesión
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION["id"];

// Obtener los productos del carrito del usuario
$consulta = $conexion->prepare("
    SELECT 
        carrito.id,
        carrito.id_producto,
        carrito.cantidad,
        productos.nombre,
        productos.descripcion,
        productos.precio,
        productos.imagen,
        productos.stock
    FROM carrito
    INNER JOIN productos 
        ON carrito.id_producto = productos.id
    WHERE carrito.id_usuario = ?
");

$consulta->bind_param("i", $id_usuario);
$consulta->execute();

$resultado = $consulta->get_result();

$productos_carrito = [];
$subtotal = 0;

while ($producto = $resultado->fetch_assoc()) {

    $producto["subtotal"] = $producto["precio"] * $producto["cantidad"];

    $subtotal += $producto["subtotal"];

    $productos_carrito[] = $producto;
}

// Envío
$envio = 500;

// Total
$total = $subtotal + $envio;

 include("includes/header.php"); ?>

<section class="banner-carrito">
    <h1>Carrito de Compras</h1>
    <p>Revisá tus productos antes de confirmar la compra.</p>
</section>

<main class="carrito">

    <?php if (count($productos_carrito) === 0): ?>

        <!-- ========================= -->
        <!-- CARRITO VACÍO -->
        <!-- ========================= -->

        <section class="carrito-vacio">

            <div class="icono-carrito-vacio">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>

            <h2>TU CARRITO ESTÁ VACÍO</h2>

            <a href="productos.php" class="boton-explorar">
                Explorar Productos
            </a>

        </section>

    <?php else: ?>

        <!-- ========================= -->
        <!-- CARRITO CON PRODUCTOS -->
        <!-- ========================= -->



        <section class="carrito-contenido">

            <div class="productos-carrito">

                <?php foreach ($productos_carrito as $producto): ?>

                    <article class="item-carrito">

                        <div class="imagen-carrito">

                            <img
                                src="img/productos/<?= htmlspecialchars($producto["imagen"]) ?>"
                                alt="<?= htmlspecialchars($producto["nombre"]) ?>"
                            >

                        </div>

                        <div class="datos-carrito">

                            <h3>
                                <?= htmlspecialchars($producto["nombre"]) ?>
                            </h3>

                            <p>
                                <?= htmlspecialchars($producto["descripcion"]) ?>
                            </p>

                            <span class="precio-unitario">
                                $<?= number_format($producto["precio"], 2, ',', '.') ?>
                            </span>

                        </div>

                        <div class="cantidad-carrito">

                            <form action="actualizar_carrito.php" method="POST">

                                <input
                                    type="hidden"
                                    name="id_producto"
                                    value="<?= $producto["id_producto"] ?>"
                                >

                                <input
                                    type="hidden"
                                    name="accion"
                                    value="restar"
                                >

                                <button type="submit">
                                    −
                                </button>

                            </form>

                            <span>
                                <?= $producto["cantidad"] ?>
                            </span>

                            <form action="actualizar_carrito.php" method="POST">

                                <input
                                    type="hidden"
                                    name="id_producto"
                                    value="<?= $producto["id_producto"] ?>"
                                >

                                <input
                                    type="hidden"
                                    name="accion"
                                    value="sumar"
                                >

                                <button type="submit">
                                    +
                                </button>

                            </form>

                        </div>

                        <div class="subtotal-carrito">

                            <strong>
                                $<?= number_format($producto["subtotal"], 2, ',', '.') ?>
                            </strong>

                        </div>

                        <div class="eliminar-carrito">

                            <form action="eliminar_carrito.php" method="POST">

                                <input
                                    type="hidden"
                                    name="id_producto"
                                    value="<?= $producto["id_producto"] ?>"
                                >

                                <button type="submit">
                                    <i class="fa-solid fa-trash"></i>
                                </button>

                            </form>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>


            <!-- ========================= -->
            <!-- RESUMEN -->
            <!-- ========================= -->

            <aside class="resumen-carrito">

                <h2>Resumen del pedido</h2>

                <div class="resumen-linea">

                    <span>Subtotal:</span>

                    <span>
                        $<?= number_format($subtotal, 2, ',', '.') ?>
                    </span>

                </div>

                <div class="resumen-linea">

                    <span>Envío:</span>

                    <span>
                        $<?= number_format($envio, 2, ',', '.') ?>
                    </span>

                </div>

                <hr>

                <div class="resumen-total">

                    <strong>Total:</strong>

                    <strong>
                        $<?= number_format($total, 2, ',', '.') ?>
                    </strong>

                </div>

                <form action="confirmarCompra.php" method="POST">

                    <button type="submit" class="btn-confirmar">
                        CONFIRMAR COMPRA
                    </button>

                </form>

                <a href="productos.php" class="btn-seguir">
                    SEGUIR COMPRANDO
                </a>

            </aside>

        </section>

    <?php endif; ?>

</main>

<?php 
include("includes/footer.php"); 
?>