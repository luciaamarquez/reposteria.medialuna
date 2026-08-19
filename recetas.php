<?php
require_once "conexion.php";

$sql = "SELECT * FROM recetas";
$resultado = $conexion->query($sql);

include("includes/header.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuestras Recetas</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Encabezado con fondo beige -->
    <header class="recetas-header">
        <h2 class="tituloNR">Nuestras recetas</h2>
        <p class="subtitulo-recetas">
            Aprendé a preparar deliciosos postres con nuestras recetas secretas
        </p>
    </header>

    <!-- Lista de tarjetas de recetas -->
    <main class="recetas-container">

        <?php while($receta = $resultado->fetch_assoc()) { ?>

        <article class="receta">

            <div class="receta-imagen">
                <img src="img/<?php echo htmlspecialchars($receta["imagen"]); ?>" alt="<?php echo htmlspecialchars($receta["titulo"]); ?>">
            </div>

            <div class="receta-contenido">

                <h3><i class="fa-solid fa-utensils icono-titulo"></i> <?php echo htmlspecialchars($receta["titulo"]); ?></h3>

                <div class="columnas">

                <div class="columna">
                    <h4>Ingredientes:</h4>
                    <ul class="lista-ingredientes">
                        <?php 
                        // Convierte el texto separado por saltos de línea en elementos de lista <li>
                        $ingredientes = explode("\n", $receta["ingredientes"]);
                        foreach($ingredientes as $ingrediente) {
                            if(!empty(trim($ingrediente))) {
                                echo "<li>" . htmlspecialchars(trim($ingrediente)) . "</li>";
                            }
                        }
                        ?>
                    </ul>
                </div>

                    <div class="columna">
                        <h4>Preparación:</h4>
                        <p><?php echo nl2br(htmlspecialchars($receta["preparacion"])); ?></p>
                    </div>

                </div>

            </div>

        </article>

        <?php } ?>

    </main>

<?php
include("includes/footer.php");
?>

</body>
</html>
