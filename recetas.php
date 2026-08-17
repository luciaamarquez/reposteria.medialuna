<?php
require_once "conexion.php";

$sql = "SELECT * FROM recetas";
$resultado = $conexion->query($sql);
?>
<head>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<section class="recetas">

    <h2 class="tituloNR">Nuestras recetas</h2>
    <p class="subtitulo-recetas">
        Aprendé a preparar deliciosos postres con nuestras recetas.
    </p>

    <?php while($receta = $resultado->fetch_assoc()) { ?>

    <div class="receta">

        <div class="receta-imagen">
            <img src="img/<?php echo $receta["imagen"]; ?>" alt="<?php echo $receta["titulo"]; ?>">
        </div>

        <div class="receta-contenido">

            <h3>🍴 <?php echo $receta["titulo"]; ?></h3>

            <div class="columnas">

                <div class="columna">
                    <h4>Ingredientes</h4>
                    <p><?php echo nl2br($receta["ingredientes"]); ?></p>
                </div>

                <div class="columna">
                    <h4>Preparación</h4>
                    <p><?php echo nl2br($receta["preparacion"]); ?></p>
                </div>

            </div>

        </div>

    </div>

    <?php } ?>

</section>