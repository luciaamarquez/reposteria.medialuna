<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

$enAdmin = strpos($_SERVER['PHP_SELF'], '/admin/') !== false;
$raiz = $enAdmin ? '../' : '';
$sesionAutenticada = isset($_SESSION["id"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medialuna</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.google.com/specimen/Xanh+Mono?categoryFilters=Feeling:%2FExpressive%2FVintage;Appearance:%2FMonospace%2FMonospace&preview.layout=grid&preview.script=Latn" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <link href="https://fonts.google.com/specimen/Cossette+Titre?preview.text=Endulz%C3%A1%20tus%0Amomentos%20especiales&preview.layout=grid&query=co&specimen.preview.text=Endulz%C3%A1+tus%0Amomentos+especiales&preview.script=Latn" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="<?php echo $raiz; ?>css/style.css">
</head>
<body>
<?php if ($sesionAutenticada): ?>
<script>
window.addEventListener("pageshow", (evento) => {
    if (evento.persisted) {
        window.location.reload();
    }
});
</script>
<?php endif; ?>
<header>
    <div class="header-contenedor">
        <div class="logo">
            <img src="<?php echo $raiz; ?>img/logo/logo.png" alt="Logo">
            <h1 class="medialuna-logo">Medialuna</h1>
        </div>
        <nav>
            <ul>
                <li>
                    <a href="<?php echo $raiz; ?>index.php#inicio">
                        Inicio
                    </a>
                </li>
                <li>
                    <a href="<?php echo $raiz; ?>index.php#nosotros">
                        Nosotros
                    </a>
                </li>
                <li>
                    <a href="<?php echo $raiz; ?>index.php#productos">
                        Productos
                    </a>
                </li>
                <li>
                    <a href="<?php echo $raiz; ?>index.php#recetas">
                        Recetas
                    </a>
                </li>
                <li>
                    <a href="<?php echo $raiz; ?>index.php#contacto">
                        Contacto
                    </a>
                </li>
            </ul>
        </nav>
        <div class="header-derecha">
            <?php if (isset($_SESSION["id"])): ?>
                <!-- Carrito -->
                <a href="<?php echo $raiz; ?>carrito.php"
                   class="icono-carrito"
                   title="Carrito">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>
                <!-- Perfil -->
                <a href="<?php echo $raiz; ?>perfil.php"
                   class="perfil-icono"
                   title="Mi perfil">
                    <i class="fa-solid fa-user"></i>
                </a>
                <!-- Panel de administración -->
                <?php if (isset($_SESSION["rol"]) && $_SESSION["rol"] == "admin"): ?>
                    <a href="<?php echo $raiz; ?>admin/index.php"
                       class="admin-icono"
                       title="Panel de administración">
                        <i class="fa-solid fa-gear"></i>
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <!-- Carrito -->
                <a href="<?php echo $raiz; ?>carrito.php"
                   class="icono-carrito"
                   title="Carrito">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>
                <!-- Iniciar sesión -->
                <a href="<?php echo $raiz; ?>login.php"
                   class="btn-ingresar">
                    INGRESAR
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>