<?php
session_start();
require_once "conexion.php";

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}
include("includes/header.php");

// Obtener todos los productos
$consulta = $conexion->query("SELECT * FROM productos ORDER BY id DESC");
$categorias = $conexion->query("SELECT id, nombre FROM categoria ORDER BY nombre");
?>

<main>
    <div id="mensaje-carrito" class="mensaje-carrito" role="status" aria-live="polite" hidden></div>

    <section class="banner-productos">

        <h1>Nuestros productos</h1>

        <p>Descubrí nuestra riquísima selección de productos artesanales</p>

    </section>

    <section class="categorias-productos">
        <button type="button" class="activo" data-categoria="todas">Todos</button>
        <?php while ($categoria = $categorias->fetch_assoc()): ?>
            <button
                type="button"
                data-categoria="<?= (int) $categoria["id"] ?>"
            ><?= htmlspecialchars($categoria["nombre"]) ?></button>
        <?php endwhile; ?>
    </section>

    <p id="sin-productos-categoria" class="sin-productos-categoria" hidden>
        No hay productos disponibles en esta categoría por el momento.
    </p>
    <section class="productos-grid">

        <?php while ($producto = $consulta->fetch_assoc()): ?>

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

        <?php endwhile; ?>

    </section>

</main>

<script>
document.querySelectorAll(".categorias-productos button").forEach((boton) => {
    boton.addEventListener("click", () => {
        const categoria = boton.dataset.categoria;
        let visibles = 0;

        document.querySelectorAll(".categorias-productos button").forEach((item) => {
            item.classList.toggle("activo", item === boton);
        });

        document.querySelectorAll(".card-producto").forEach((producto) => {
            producto.hidden = categoria !== "todas" && producto.dataset.categoria !== categoria;
            if (!producto.hidden) {
                visibles++;
            }
        });

        document.getElementById("sin-productos-categoria").hidden = visibles > 0;
    });
});

const mensajeCarrito = document.getElementById("mensaje-carrito");
document.querySelectorAll(".productos-grid form").forEach((formulario) => {
    formulario.addEventListener("submit", async (evento) => {
        evento.preventDefault();
        const boton = formulario.querySelector("button[type='submit']");
        boton.disabled = true;

        try {
            const respuesta = await fetch(formulario.action, {
                method: "POST",
                body: new FormData(formulario),
                headers: {
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            });
            const datos = await respuesta.json();
            mensajeCarrito.textContent = datos.message;
            mensajeCarrito.classList.toggle("error", !respuesta.ok);
            mensajeCarrito.hidden = false;
            window.setTimeout(() => {
                mensajeCarrito.hidden = true;
            }, 3500);
        } catch (error) {
            mensajeCarrito.textContent = "No se pudo agregar el producto. Intentá nuevamente.";
            mensajeCarrito.classList.add("error");
            mensajeCarrito.hidden = false;
        } finally {
            boton.disabled = false;
        }
    });
});
</script>

<?php include("includes/footer.php"); ?>