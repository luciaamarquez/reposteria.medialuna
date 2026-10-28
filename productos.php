<?php
session_start();
require_once "conexion.php";

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}
include("includes/header.php");

// Obtener todos los productos
$productos = $conexion->query("SELECT * FROM productos ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
$categorias = $conexion->query("SELECT id, nombre FROM categoria ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
?>

<main>
    <div id="mensaje-carrito" class="mensaje-carrito" role="status" aria-live="polite" hidden></div>

    <section class="banner-productos">

        <h1>Nuestros productos</h1>

        <p>Descubrí nuestra riquísima selección de productos artesanales</p>

    </section>

    <section class="categorias-productos">
        <button type="button" class="activo" data-categoria="todas">Todos</button>
        <?php foreach ($categorias as $categoria): ?>
            <button
                type="button"
                data-categoria="<?= (int) $categoria["id"] ?>"
                data-categoria-slug="<?= htmlspecialchars(strtolower($categoria["nombre"]), ENT_QUOTES) ?>"
            ><?= htmlspecialchars($categoria["nombre"]) ?></button>
        <?php endforeach; ?>
    </section>

    <p id="sin-productos-categoria" class="sin-productos-categoria" hidden>
        No hay productos disponibles en esta categoría por el momento.
    </p>
    <section class="productos-grid">

        <?php foreach ($productos as $producto): ?>

            <?php if ($producto['stock'] > 0): ?>

                <div class="card-producto" data-categoria="<?= (int) $producto["id_categoria"] ?>">
                    <img
                        src="img/productos/<?= htmlspecialchars($producto['imagen']) ?>"
                        alt="<?= htmlspecialchars($producto['nombre']) ?>"
                    >

                    <div class="info-producto">

                        <div>

                            <h3>
                                <?= htmlspecialchars($producto['nombre']) ?>
                            </h3>

                            <p>
                                <?= htmlspecialchars($producto['descripcion']) ?>
                            </p>

                            <span>
                                $<?= number_format($producto['precio'], 2, ',', '.') ?>
                            </span>

                        </div>

                        <form action="agregar_carrito.php" method="POST">

                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
                            <input
                                type="hidden"
                                name="id_producto"
                                value="<?= $producto['id'] ?>"
                            >

                            <label class="cantidad-producto">
                                <span class="visualmente-oculto">Cantidad</span>
                                <input type="number" name="cantidad" value="1" min="1" max="<?= (int) $producto["stock"] ?>" required>
                            </label>

                            <button type="submit" aria-label="Agregar <?= htmlspecialchars($producto["nombre"]) ?> al carrito">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </button>

                        </form>

                    </div>

                </div>

            <?php else: ?>

                <!-- Producto agotado -->

                <div class="card-producto agotado" data-categoria="<?= (int) $producto["id_categoria"] ?>">

                    <div class="imagen-agotada">
                        No disponible
                    </div>

                    <div class="info-producto">

                        <div>

                            <h3>
                                <?= htmlspecialchars($producto['nombre']) ?>
                            </h3>

                            <p>
                                <?= htmlspecialchars($producto['descripcion']) ?>
                            </p>

                            <span id="precio-agotado">
                                AGOTADO
                            </span>

                        </div>

                        <button type="button" disabled>
                            <i class="fa-solid fa-cart-shopping"></i>
                        </button>

                    </div>

                </div>

            <?php endif; ?>

        <?php endforeach; ?>

    </section>

</main>

<?php include("includes/footer.php"); ?>