<?php
session_start();
include("includes/header.php");
?>

<a href="#inicio" id="btnSubir">
    <i class="fa-solid fa-arrow-up"></i>
</a>

<main>
    <section class="banner" id="inicio">
        <div class="banner-texto">
            <h2>Endulzá tus <br> momentos especiales</h2>

            <p>
                Descubrí nuestras deliciosas creaciones artesanales de
                pastelería y repostería, hechas con amor y los mejores
                ingredientes.
            </p>

            <div class="botones-banner">
                <a href="productos.php" class="btn-principal">VER PRODUCTOS</a>
                <a href="recetas.php" class="btn-secundario">VER RECETAS</a>
            </div>
        </div>

        <div class="banner-imagen">
            <img src="img/banner/cafeteriaIA.png" alt="img">
        </div> 
    </section>


<!-- CATEGORÍAS -->

    <section class="categorias" id="productos">
        <h2>Nuestra variedad de productos</h2>

        <div class="fila">

        <a href="productos.php?categoria=donuts" class="categoria enlace-categoria">
            <div class="imagen-categoria">
                <img src="img/categorias/donuts.png" alt="Donuts">
                <div class="overlay">
                    <p>Esponjosas y glaseadas artesanalmente.</p>
                </div>
            </div>
            <p>Donuts</p>
        </a>

        <a href="productos.php?categoria=budines" class="categoria enlace-categoria">
            <div class="imagen-categoria">
                <img src="img/categorias/budines.png" alt="Budines">
                <div class="overlay">
                    <p>Budines caseros con ingredientes frescos.</p>
                </div>
            </div>
            <p>Budines</p>
        </a>

        <a href="productos.php?categoria=muffins" class="categoria enlace-categoria">
            <div class="imagen-categoria">
                <img src="img/categorias/muffins.png" alt="Muffins">
                <div class="overlay">
                    <p>Muffins suaves con distintos sabores.</p>
                </div>
            </div>
            <p>Muffins</p>
        </a>

        </div>

        <div class="fila">

        <a href="productos.php?categoria=tortas" class="categoria enlace-categoria">
            <div class="imagen-categoria">
                <img src="img/categorias/tortas.png" alt="Tortas">
                <div class="overlay">
                    <p>Tortas artesanales para cada ocasión.</p>
                </div>
            </div>
            <p>Tortas</p>
        </a>

        <a href="productos.php?categoria=cookies" class="categoria enlace-categoria">
            <div class="imagen-categoria">
                <img src="img/categorias/cookies.jpg" alt="Cookies">
                <div class="overlay">
                    <p>Cookies crocantes de distintos sabores.</p>
                </div>
            </div>
            <p>Cookies</p>
        </a>
        </div>
        <a href="productos.php" class="btn-productos">VER TODOS LOS PRODUCTOS</a>
    </section>

<!-- SOBRE NOSOTROS -->

    <section class="nosotros" id="nosotros">
        <h2>Sobre Nosotros</h2>
        <div class="contenedor-nosotros">

            <div class="nosotros-card">
                <div class="icono">
                    <i class="fa-solid fa-heart"></i>
                </div>
                <h3>Pasión</h3>
                <p>
                    Cada producto es elaborado con amor y dedicación,
                    poniendo el corazón en cada detalle.
                </p>
            </div>

            <div class="nosotros-card">
                <div class="icono">
                    <i class="fa-solid fa-truck"></i>
                </div>
                <h3>Compromiso</h3>
                <p>
                    Entregamos tus pedidos frescos y
                    en el tiempo acordado.
                </p>
            </div>

            <div class="nosotros-card">
                <div class="icono">
                    <i class="fa-solid fa-star"></i>
                </div>
                <h3>Calidad</h3>
                <p>
                    Utilizamos solo ingredientes premium
                    para garantizar el mejor sabor y textura.
                </p>
            </div>
        </div>
    </section>

<!-- RECETAS (vista previa) -->
<section class="recetas" id="recetas">
    <div class="recetas-contenedor">
        <div class="recetas-img">
            <img src="img/recetas/imgR1.jpg" class="slide activo">
            <img src="img/recetas/imgR2.jpg" class="slide">
            <img src="img/recetas/imgR3.jpg" class="slide">
            <img src="img/recetas/imgR4.jpg" class="slide">
            <img src="img/recetas/imgR5.jpg" class="slide">
            <img src="img/recetas/imgR6.jpg" class="slide">
            <img src="img/recetas/imgR7.jpg" class="slide">
            <img src="img/recetas/imgR8.jpg" class="slide">
        </div>

        <div class="recetas-texto">
        <h2>Recetas</h2>

        <p class="descripcion">
        En Medialuna creemos que los mejores momentos comienzan en la cocina.
        Descubrí recetas artesanales, explicadas paso a paso para que puedas 
        recrearlas en casa con ingredientes simples y mucho amor.
        </p>

        <div class="linea"></div>
            <blockquote class="frase" id="frase"></blockquote>
            <a href="recetas.php" class="btn-productos">
                VER TODAS LAS RECETAS
            </a>
        </div>
    </div>
    </section>
</main>

<?php
include("includes/footer.php");
?>