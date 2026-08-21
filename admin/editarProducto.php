<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../login.php");
    exit();
}

require_once "../conexion.php";

$id = $_GET["id"];

$consulta = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
$consulta->bind_param("i", $id);
$consulta->execute();

$resultado = $consulta->get_result();
$producto = $resultado->fetch_assoc();

$categorias = $conexion->query("SELECT id, nombre FROM categoria");

include("../includes/header.php");
?>

<div class="editar-receta">
    <div class="editar-receta-card">

        <h1>Editar Producto</h1>

        <form action="actualizarProducto.php" method="POST">

            <input type="hidden" name="id" value="<?php echo $producto["id"]; ?>">

            <div class="editar-grupo">
                <label>Nombre</label>
                <input type="text" name="nombre"
                    value="<?php echo htmlspecialchars($producto["nombre"]); ?>" required>
            </div>

            <div class="editar-grupo">
                <label>Descripción</label>
                <textarea name="descripcion" required><?php echo htmlspecialchars($producto["descripcion"]); ?></textarea>
            </div>

            <div class="doble-textarea">

                <div class="editar-grupo">
                    <label>Precio</label>
                    <input type="number" step="0.01" name="precio"
                        value="<?php echo $producto["precio"]; ?>" required>
                </div>

                <div class="editar-grupo">
                    <label>Stock</label>
                    <input type="number" name="stock"
                        value="<?php echo $producto["stock"]; ?>" required>
                </div>

            </div>

            <div class="editar-grupo">
                <label>Imagen</label>
                <input type="text" name="imagen"
                    value="<?php echo htmlspecialchars($producto["imagen"]); ?>" required>
            </div>

            <div class="editar-grupo">
                <label>Categoría</label>

                <select name="id_categoria" required>

                    <?php while($categoria = $categorias->fetch_assoc()) { ?>

                        <option value="<?php echo $categoria["id"]; ?>"
                            <?php if($categoria["id"] == $producto["id_categoria"]) echo "selected"; ?>>

                            <?php echo htmlspecialchars($categoria["nombre"]); ?>

                        </option>

                    <?php } ?>

                </select>

            </div>

            <div class="editar-botones">
                <a href="index.php" class="btn-volver-editar">Cancelar</a>
                <button type="submit" class="btn-actualizar">Actualizar</button>
            </div>

        </form>

    </div>
</div>

<?php include("../includes/footer.php"); ?>