<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$enAdmin = strpos($_SERVER['PHP_SELF'], '/admin/') !== false;
$raiz = $enAdmin ? '../' : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medialuna</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="<?php echo $raiz; ?>CSS/style.css">
</head>
<body>
<header>
    <div class="header-contenedor">
        <div class="logo">
            <img src="<?php echo $raiz; ?>img/logo/logo.png" alt="Logo">
            <h1>MEDIALUNA</h1>
        </div>
        <nav>
            <ul>
                <li>
                    <a href="<?php echo $raiz; ?>index.php#inicio">
                        INICIO
                    </a>
                </li>
                <li>
                    <a href="<?php echo $raiz; ?>index.php#nosotros">
                        NOSOTROS
                    </a>
                </li>
                <li>
                    <a href="<?php echo $raiz; ?>index.php#productos">
                        PRODUCTOS
                    </a>
                </li>
                <li>
                    <a href="<?php echo $raiz; ?>index.php#recetas">
                        RECETAS
                    </a>
                </li>
                <li>
                    <a href="<?php echo $raiz; ?>index.php#contacto">
                        CONTACTO
                    </a>
                </li>
            </ul>
        </nav>
        <div class="header-derecha">
            <?php if (isset($_SESSION["id"])): ?>
                <!-- Carrito -->
                <a href="<?php echo $raiz; ?>carrito.php"
                   class="carrito"
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
                   class="carrito"
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