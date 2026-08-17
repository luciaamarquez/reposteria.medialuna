<?php

session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../login.php");
    exit();
}

include("../conexion.php");
include("../includes/header.php");


// ==============================
// OBTENER PRODUCTOS
// ==============================

$sqlProductos = "SELECT * FROM productos ORDER BY id DESC";
$resultadoProductos = $conexion->query($sqlProductos);


// ==============================
// OBTENER RECETAS
// ==============================

$sqlRecetas = "SELECT * FROM recetas ORDER BY id DESC";
$resultadoRecetas = $conexion->query($sqlRecetas);

?>

<main class="panel-admin">


    <!-- ==========================
         ENCABEZADO DEL PANEL
    =========================== -->

    <div class="Contenedor_panel">

        <h1 class="titulo_panel_admin">
            Panel de Administración
        </h1>

        <div class="subcontenedor_panel">

            <p>
                Bienvenido,
                <?php echo htmlspecialchars($_SESSION["nombre"]); ?>.
            </p>

            <a class="Tienda_panel" href="../index.php">
                <i class="fa-solid fa-store"></i>
                Ir a la tienda
            </a>

        </div>

    </div>


    <!-- ==========================
         PESTAÑAS
    =========================== -->

    <div class="admin-panel-contenido">

        <div class="admin-pestanas">

            <button
                class="pestana activa"
                onclick="mostrarSeccion('productos', this)"
            >
                <i class="fa-solid fa-box"></i>
                GESTIONAR PRODUCTOS
            </button>


            <button
                class="pestana"
                onclick="mostrarSeccion('recetas', this)"
            >
                <i class="fa-solid fa-utensils"></i>
                GESTIONAR RECETAS
            </button>

        </div>


        <!-- ==========================
             PRODUCTOS
        =========================== -->

        <section id="productos" class="admin-seccion activa">

            <div class="admin-titulo">

                <h2>Productos</h2>

                <a href="agregar_producto.php" class="btn-agregar">
                    <i class="fa-solid fa-plus"></i>
                    AGREGAR PRODUCTO
                </a>

            </div>


            <div class="tabla-contenedor">

                <table class="tabla-admin">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Acciones</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php while ($producto = $resultadoProductos->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    <?php echo $producto["id"]; ?>
                                </td>


                                <td>

                                    <?php if (!empty($producto["imagen"])): ?>

                                        <img
                                            src="../img/productos/<?php echo htmlspecialchars($producto["imagen"]); ?>"
                                            class="imagen-producto-admin"
                                            alt="Producto"
                                        >

                                    <?php else: ?>

                                        <span>Sin imagen</span>

                                    <?php endif; ?>

                                </td>


                                <td>
                                    <?php echo htmlspecialchars($producto["nombre"]); ?>
                                </td>


                                <td class="descripcion-tabla">
                                    <?php echo htmlspecialchars($producto["descripcion"]); ?>
                                </td>


                                <td>
                                    <?php echo $producto["id_categoria"]; ?>
                                </td>


                                <td>
                                    $<?php echo number_format(
                                        $producto["precio"],
                                        2,
                                        ',',
                                        '.'
                                    ); ?>
                                </td>


                                <td>
                                    <?php echo $producto["stock"]; ?>
                                </td>


                                <td class="acciones">

                                    <a
                                        href="editarProducto.php?id=<?php echo $producto["id"]; ?>"
                                        class="btn-editar"
                                        title="Editar"
                                    >
                                        <i class="fa-solid fa-pen"></i>
                                    </a>


                                    <a
                                        href="eliminarProducto.php?id=<?php echo $producto["id"]; ?>"
                                        class="btn-eliminar"
                                        title="Eliminar"
                                        onclick="return confirm('¿Seguro que querés eliminar este producto?');"
                                    >
                                        <i class="fa-solid fa-trash"></i>
                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </section>


        <!-- ==========================
             RECETAS
        =========================== -->

        <section id="recetas" class="admin-seccion">

            <div class="admin-titulo">

                <h2>Recetas</h2>

                <a href="agregarReceta.php" class="btn-agregar">
                    <i class="fa-solid fa-plus"></i>
                    AGREGAR RECETA
                </a>

            </div>


            <div class="tabla-contenedor">

                <table class="tabla-admin">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Título</th>
                            <th>Ingredientes</th>
                            <th>Preparación</th>
                            <th>Acciones</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php while ($receta = $resultadoRecetas->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    <?php echo $receta["id"]; ?>
                                </td>


                                <td>

                                    <?php if (!empty($receta["imagen"])): ?>

                                        <img
                                            src="../img/recetas/<?php echo htmlspecialchars($receta["imagen"]); ?>"
                                            class="imagen-receta-admin"
                                            alt="Receta"
                                        >

                                    <?php else: ?>

                                        <span>Sin imagen</span>

                                    <?php endif; ?>

                                </td>


                                <td>
                                    <?php echo htmlspecialchars($receta["titulo"]); ?>
                                </td>


                                <td class="descripcion-tabla">
                                    <?php echo htmlspecialchars($receta["ingredientes"]); ?>
                                </td>


                                <td class="descripcion-tabla">
                                    <?php echo htmlspecialchars($receta["preparacion"]); ?>
                                </td>


                                <td class="acciones">

                                    <a
                                        href="editarReceta.php?id=<?php echo $receta["id"]; ?>"
                                        class="btn-editar"
                                        title="Editar"
                                    >
                                        <i class="fa-solid fa-pen"></i>
                                    </a>


                                    <a
                                        href="eliminarReceta.php?id=<?php echo $receta["id"]; ?>"
                                        class="btn-eliminar"
                                        title="Eliminar"
                                        onclick="return confirm('¿Estás seguro de eliminar esta receta?');"
                                    >
                                        <i class="fa-solid fa-trash"></i>
                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </section>

    </div>

</main>


<!-- ==========================
     CAMBIO DE PESTAÑAS
=========================== -->

<script>

function mostrarSeccion(seccion, boton) {

    // Ocultar todas las secciones

    document.querySelectorAll(".admin-seccion").forEach(function(elemento) {

        elemento.classList.remove("activa");

    });


    // Quitar activa de todos los botones

    document.querySelectorAll(".pestana").forEach(function(elemento) {

        elemento.classList.remove("activa");

    });


    // Mostrar la sección elegida

    document.getElementById(seccion).classList.add("activa");


    // Activar el botón

    boton.classList.add("activa");

}

</script>


<?php

include("../includes/footer.php");

?>