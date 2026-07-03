<?php
include("includes/header.php");
?>

<main>

    <section class="banner">

        <div class="banner-texto">

            <h2>Endulzá tus<br>momentos especiales</h2>

            <p>
                Descubrí nuestras deliciosas creaciones artesanales
                de pastelería y repostería, hechas con amor y los
                mejores ingredientes.
            </p>

            <a href="productos.php" class="btn-banner">
                VER TODOS LOS PRODUCTOS
            </a>

        </div>

        <div class="banner-imagen">

            <img src="img/banner/banner.jfif" alt="Pastelería">

        </div>

    </section>

     <!-- PRODUCTOS -->

     <section class="categorias">

<h2>Nuestra variedad de productos</h2>

<div class="contenedor-categorias">

    <div class="categoria">
        <img src="img/productos/donuts.jpg" alt="Donuts">
        <h3>Donuts</h3>
    </div>

    <div class="categoria">
        <img src="img/productos/budines.jpg" alt="Budines">
        <h3>Budines</h3>
    </div>

    <div class="categoria">
        <img src="img/productos/muffins.jpg" alt="Muffins">
        <h3>Muffins</h3>
    </div>

    <div class="categoria">
        <img src="img/productos/tortas.jpg" alt="Tortas">
        <h3>Tortas</h3>
    </div>

    <div class="categoria">
        <img src="img/productos/cookies.jpg" alt="Cookies">
        <h3>Cookies</h3>
    </div>

</div>

</section>

<!-- SOBRE NOSOTROS -->

<section class="nosotros">

    <h2>Sobre Nosotros</h2>

    <div class="contenedor-nosotros">

        <div class="nosotros-card">

            <div class="icono">❤</div>

            <h3>Pasión</h3>

            <p>
                Cada producto es elaborado con amor y dedicación,
                poniendo el corazón en cada detalle.
            </p>

        </div>

        <div class="nosotros-card">

            <div class="icono">🚚</div>

            <h3>Compromiso</h3>

            <p>
                Entregamos tus pedidos frescos y
                en el tiempo acordado.
            </p>

        </div>

        <div class="nosotros-card">

            <div class="icono">★</div>

            <h3>Calidad</h3>

            <p>
                Utilizamos solo ingredientes premium
                para garantizar el mejor sabor y textura.
            </p>

        </div>

    </div>

</section>

</main>

<?php
include("includes/footer.php");
?>