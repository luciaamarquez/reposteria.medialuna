<?php

session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../login.php");
    exit();
}

include("../conexion.php");
include("../includes/header.php");


// OBTENER PRODUCTOS DE LA BASE DE DATOS

$sql = "SELECT * FROM productos ORDER BY id DESC";
$resultado = $conexion->query($sql);

?>

<main class="admin-contenedor">

    <div class="admin-titulo">

        <h1>Productos</h1>

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

                <?php while ($producto = $resultado->fetch_assoc()): ?>

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


                        <td>
                            <?php echo htmlspecialchars($producto["descripcion"]); ?>
                        </td>


                        <td>
                            <?php echo $producto["id_categoria"]; ?>
                        </td>


                        <td>
                            $<?php echo number_format($producto["precio"], 2, ',', '.'); ?>
                        </td>


                        <td>
                            <?php echo $producto["stock"]; ?>
                        </td>


                        <td class="acciones">

                            <a
                                href="editar_producto.php?id=<?php echo $producto["id"]; ?>"
                                class="btn-editar"
                                title="Editar"
                            >
                                <i class="fa-solid fa-pen"></i>
                            </a>


                            <a
                                href="eliminar_producto.php?id=<?php echo $producto["id"]; ?>"
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

</main>


<?php

include("../includes/footer.php");

?>