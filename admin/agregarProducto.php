<?php
session_start();
require_once "../conexion.php";
$categorias = $conexion->query("SELECT id, nombre FROM categoria");
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agregar Producto</title>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    </head>
    <body>
        <main class="editar-receta">
            <section class="editar-receta-card">
                <h1>Agregar Producto</h1>

                <form action="guardarProducto.php" method="POST" enctype="multipart/form-data">
                    <div class="editar-grupo">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>

                    <div class="editar-grupo">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" name="descripcion" required></textarea>
                    </div>

                    <div class="doble-textarea">
                        <div class="editar-grupo">
                            <label for="precio">Precio</label>
                            <input type="number" step="0.01" min="0.01" id="precio" name="precio" required>
                        </div>

                        <div class="editar-grupo">
                            <label for="stock">Stock</label>
                            <input type="number" min="0" max="30" id="stock" name="stock" required>
                        </div>
                    </div>

                    <div class="editar-grupo">
                        <label for="imagen">Imagen</label>
                        <input type="file" id="imagen" name="imagen" accept="image/jpeg,image/png,image/webp,image/gif" required>
                    </div>

                    <div class="editar-grupo">
                        <label for="id_categoria">Categoría</label>
                        <select id="id_categoria" name="id_categoria" required>
                            <option value="">Seleccione una categoría</option>
                            <?php while ($categoria = $categorias->fetch_assoc()) { ?>
                                <option value="<?php echo $categoria["id"]; ?>">
                                    <?php echo htmlspecialchars($categoria["nombre"]); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="editar-botones">
                        <a href="index.php" class="btn-volver-editar">CANCELAR</a>
                        <button type="submit" class="btn-actualizar">GUARDAR PRODUCTO</button>
                    </div>
                </form>
            </section>
        </main>
        <script src="../js/script.js" defer></script>
    </body>
</html>