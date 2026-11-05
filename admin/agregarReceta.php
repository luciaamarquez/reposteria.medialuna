<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

include("../includes/header.php");
?>

<main class="editar-receta">
    <section class="editar-receta-card">
        <h1>Agregar receta</h1>

        <form action="guardarReceta.php" method="POST" enctype="multipart/form-data">
            <div class="editar-grupo">
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>

            <div class="doble-textarea">
                <div class="editar-grupo">
                    <label for="ingredientes">Ingredientes</label>
                    <textarea id="ingredientes" name="ingredientes" required></textarea>
                </div>
                <div class="editar-grupo">
                    <label for="preparacion">Preparación</label>
                    <textarea id="preparacion" name="preparacion" required></textarea>
                </div>
            </div>

            <div class="editar-grupo">
                <label for="imagen">Imagen</label>
                <input type="file" id="imagen" name="imagen" accept="image/jpeg,image/png,image/webp,image/gif" required>
                <small>JPG, PNG, WEBP o GIF; máximo 5 MB.</small>
            </div>

            <div class="editar-botones">
                <a href="recetas.php" class="btn-volver-editar">CANCELAR</a>
                <button type="submit" class="btn-actualizar">AGREGAR RECETA</button>
            </div>
        </form>
    </section>
</main>

<?php include("../includes/footer.php"); ?>
