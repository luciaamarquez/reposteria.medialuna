<?php

session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../login.php");
    exit();
}

include("../conexion.php");
include("../includes/header.php");


// OBTENER PRODUCTOS DE LA BASE DE DATOS

$sql = "
    SELECT productos.*, categoria.nombre AS categoria_nombre
    FROM productos
    LEFT JOIN categoria ON productos.id_categoria = categoria.id
    ORDER BY productos.id DESC
";
$resultado = $conexion->query($sql);

?>

<main class="admin-contenedor">

    <div class="admin-titulo">

        <h1>Productos</h1>

        <a href="agregarProducto.php" class="btn-agregar">
            <i class="fa-solid fa-plus"></i>
            AGREGAR PRODUCTO
        </a>

    </div>


    <div class="tabla-contenedor">

        <table class="tabla-admin tabla-productos-admin">

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

                <?php while ($producto = $resultado->fetch_assoc()): ?>

                    <tr>

                        <td data-label="ID">
                            <?php echo $producto["id"]; ?>
                        </td>


                        <td data-label="Imagen">

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


                        <td data-label="Nombre">
                            <?php echo htmlspecialchars($producto["nombre"]); ?>
                        </td>


                        <td data-label="Descripción" class="descripcion-tabla">
                            <?php echo htmlspecialchars($producto["descripcion"]); ?>
                        </td>


                        <td data-label="Categoría">
                            <?php echo htmlspecialchars($producto["categoria_nombre"] ?? "Sin categoría"); ?>
                        </td>


                        <td data-label="Precio">
                            $<?php echo number_format($producto["precio"], 2, ',', '.'); ?>
                        </td>


                        <td data-label="Stock">
                            <?php echo $producto["stock"]; ?>
                        </td>


                        <td class="acciones" data-label="Acciones">

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
                                data-confirm="¿Seguro que querés eliminar este producto?">
                                <i class="fa-solid fa-trash"></i>
                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</main>


<?php

include("../includes/footer.php");

?>