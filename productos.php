<?php
session_start();
require_once "conexion.php";
include("includes/header.php");

// Obtener todos los productos
$consulta = $conexion->query("SELECT * FROM productos ORDER BY id DESC");
?>

<main>

    <section class="banner-productos">

        <h1>Nuestros productos</h1>

        <p>Descubrí nuestra riquísima selección de productos artesanales</p>

    </section>

    <section class="categorias-productos">

        <button>Donuts</button>

        <button class="activo">Budines</button>

        <button>Muffins</button>

        <button>Tortas</button>

        <button>Cookies</button>

    </section>

    <section class="productos-grid">

        <?php while ($producto = $consulta->fetch_assoc()): ?>

            <?php if ($producto['stock'] > 0): ?>

                <div class="card-producto">

                    <a href="producto.php?id=<?= $producto['id'] ?>">
                        <img
                            src="img/productos/<?= htmlspecialchars($producto['imagen']) ?>"
                            alt="<?= htmlspecialchars($producto['nombre']) ?>"
                        >
                    </a>

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

                            <input
                                type="hidden"
                                name="id_producto"
                                value="<?= $producto['id'] ?>"
                            >

                            <button type="submit">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </button>

                        </form>

                    </div>

                </div>

            <?php else: ?>

                <!-- Producto agotado -->

                <div class="card-producto agotado">

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

                            <span>
                                AGOTADO
                            </span>

                        </div>

                        <button type="button" disabled>
                            <i class="fa-solid fa-cart-shopping"></i>
                        </button>

                    </div>

                </div>

            <?php endif; ?>

        <?php endwhile; ?>

    </section>

</main>

<?php include("includes/footer.php"); ?>