<?php
include("includes/header.php");
?>

<a href="#inicio" id="btnSubir">
    <i class="fa-solid fa-arrow-up"></i>
</a>
<script>
    const btnSubir = document.getElementById("btnSubir");

window.addEventListener("scroll", () => {

    if(window.scrollY > 400){
        btnSubir.classList.add("mostrar");
    }else{
        btnSubir.classList.remove("mostrar");
    }

});
</script>

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
                <a href="productos.php" class="btn-principal">Ver Productos</a>
                <a href="recetas.php" class="btn-secundario">Ver Recetas</a>
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

        <div class="categoria">
            <div class="imagen-categoria">
                <img src="img/categorias/donuts.png" alt="Donuts">
                <div class="overlay">
                    <p>Esponjosas y glaseadas artesanalmente.</p>
                </div>
            </div>
            <p>Donuts</p>
        </div>

        <div class="categoria">
            <div class="imagen-categoria">
                <img src="img/categorias/budines.png" alt="Budines">
                <div class="overlay">
                    <p>Budines caseros con ingredientes frescos.</p>
                </div>
            </div>
            <p>Budines</p>
        </div>

        <div class="categoria">
            <div class="imagen-categoria">
                <img src="img/categorias/muffins.png" alt="Muffins">
                <div class="overlay">
                    <p>Muffins suaves con distintos sabores.</p>
                </div>
            </div>
            <p>Muffins</p>
        </div>

        </div>

        <div class="fila">

        <div class="categoria">
            <div class="imagen-categoria">
                <img src="img/categorias/tortas.png" alt="Tortas">
                <div class="overlay">
                    <p>Tortas artesanales para cada ocasión.</p>
                </div>
            </div>
            <p>Tortas</p>
        </div>

        <div class="categoria">
            <div class="imagen-categoria">
                <img src="img/categorias/cookies.jpg" alt="Cookies">
                <div class="overlay">
                    <p>Cookies crocantes de distintos sabores.</p>
                </div>
            </div>
            <p>Cookies</p>
        </div>

        </div>

        <a href="productos.php" class="btn-productos">VER TODOS LOS PRODUCTOS</a>

        <script>

            const categorias = document.querySelectorAll(".categoria");

            const observer = new IntersectionObserver((entries) => {

                entries.forEach((entry) => {

                    if(entry.isIntersecting){
                        entry.target.classList.add("mostrar");
                    }

                });

            });

            categorias.forEach((categoria)=>{
                observer.observe(categoria);
            });

        </script>
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

        <script>

            const cards = document.querySelectorAll(".nosotros-card");

            const observerNosotros = new IntersectionObserver((entries) => {

                if (entries[0].isIntersecting) {

                    cards.forEach((card, index) => {

                        setTimeout(() => {
                            card.classList.add("mostrar");
                        }, index * 350);

                    });

                    observerNosotros.disconnect();
                }

            },{
                threshold:0.4
            });

            observerNosotros.observe(document.querySelector(".contenedor-nosotros"));
        </script>
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

<script>

const slides = document.querySelectorAll(".slide");
let actual = 0;

setInterval(() => {

    slides[actual].classList.remove("activo");

    actual++;

    if(actual >= slides.length){
        actual = 0;
    }

    slides[actual].classList.add("activo");

}, 3000);

const texto = "Cada receta guarda una historia, cada aroma crea un recuerdo.";
const frase = document.getElementById("frase");

let i = 0;

function escribir() {
    if (i < texto.length) {
        frase.textContent += texto.charAt(i);
        i++;
        setTimeout(escribir, 50); // velocidad
    }
}

const observerFrase = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting) {
        escribir();
        observerFrase.disconnect();
    }
}, {
    threshold: 0.5
});

observerFrase.observe(frase);
</script>

</section>
</main>

<?php
include("includes/footer.php");
?>