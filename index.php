<?php
session_start();
include("includes/header.php");

// Si es admin, mostrar panel de administración
if (isset($_SESSION["rol"]) && $_SESSION["rol"] == "admin") {
    ?>
    <main>
        <h1>Panel de Administración</h1>
        <p>Bienvenido, <?php echo $_SESSION["nombre"]; ?>.</p>
        <hr>
        <ul>
            <li><a href="admin/productos.php">Gestionar productos</a></li>
            <li><a href="admin/recetas.php">Gestionar recetas</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
    </main>
    <?php
    include("includes/footer.php");
    exit();
}
?>

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
            <img src="img/banner/banner.png" alt="Banner">
        </div> 
    </section>


<!-- CATEGORÍAS -->

    <section class="categorias" id="productos">
        <h2>Nuestra variedad de productos</h2>

        <div class="fila">
            <div class="categoria">
                <img src="img/productos/donuts.jpg" alt="Donuts">
                <p>Donuts</p>
            </div>

            <div class="categoria">
                <img src="img/productos/budines.jpg" alt="Budines">
                <p>Budines</p>
            </div>

            <div class="categoria">
                <img src="img/productos/muffins.jpg" alt="Muffins">
                <p>Muffins</p>
            </div>
        </div>

        <div class="fila">
            <div class="categoria">
                <img src="img/productos/tortas.jpg" alt="Tortas">
                <p>Tortas</p>
            </div>

            <div class="categoria">
                <img src="img/productos/cookies.jpg" alt="Cookies">
                <p>Cookies</p>
            </div>
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
        <h2>Recetas</h2>
        
        <p>
            Descubrí recetas fáciles y deliciosas para 
            preparar en casa.
        </p>

        <a href="recetas.php" class="btn-productos">VER TODAS LAS RECETAS</a>
    </section>
</main>

<?php
include("includes/footer.php");
?>