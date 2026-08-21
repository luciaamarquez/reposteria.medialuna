<?php
session_start();
require_once "conexion.php";

// Verificar que haya un usuario logueado
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION["id"];

// Buscar los datos del usuario
$consulta = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
$consulta->bind_param("i", $id);
$consulta->execute();

$resultado = $consulta->get_result();

if ($resultado->num_rows != 1) {
    echo "Usuario no encontrado.";
    exit();
}

$usuario = $resultado->fetch_assoc();

include("includes/header.php");
?>

<main class="perfil">

    <div class="perfil-titulo">
        <h1>Mi Perfil</h1>
        <p>Administrá la información de tu cuenta.</p>
    </div>

    <div class="perfil-layout">

        <!-- CARD IZQUIERDA -->
        <aside class="card-usuario">

            <div class="perfil-icono">
                <i class="fa-solid fa-user"></i>
            </div>

            <h2>
                <?php echo htmlspecialchars($usuario["nombre"]); ?>
                <?php echo htmlspecialchars($usuario["apellido"]); ?>
            </h2>

            <span class="perfil-rol">
                <?php echo ucfirst($usuario["rol"]); ?>
            </span>

            <div class="info-usuario">

                <p>
                    <i class="fa-solid fa-envelope"></i>
                    <?php echo htmlspecialchars($usuario["email"]); ?>
                </p>

                <p>
                    <i class="fa-solid fa-phone"></i>
                    <?php echo htmlspecialchars($usuario["telefono"]); ?>
                </p>

                <p>
                    <i class="fa-solid fa-location-dot"></i>
                    <?php echo htmlspecialchars($usuario["direccion"]); ?>
                </p>

            </div>

            <a href="logout.php" class="btn-salir">
                <i class="fa-solid fa-right-from-bracket"></i>
                Cerrar sesión
            </a>

        </aside>


        <!-- CARD DERECHA -->
        <section>

            <div class="card-editar">

                <h3>
                    <i class="fa-solid fa-pen"></i>
                    Información personal
                </h3>

                <form action="actualizar_perfil.php" method="POST">

                    <div class="fila-form">

                        <div class="campo">
                            <label>Nombre</label>
                            <input type="text" name="nombre"
                                value="<?php echo htmlspecialchars($usuario["nombre"]); ?>">
                        </div>

                        <div class="campo">
                            <label>Apellido</label>
                            <input type="text" name="apellido"
                                value="<?php echo htmlspecialchars($usuario["apellido"]); ?>">
                        </div>

                    </div>

                    <div class="campo">
                        <label>Email</label>
                        <input type="email" name="email"
                            value="<?php echo htmlspecialchars($usuario["email"]); ?>">
                    </div>

                    <div class="fila-form">

                        <div class="campo">
                            <label>Teléfono</label>
                            <input type="text" name="telefono"
                                value="<?php echo htmlspecialchars($usuario["telefono"]); ?>">
                        </div>

                        <div class="campo">
                            <label>Dirección</label>
                            <input type="text" name="direccion"
                                value="<?php echo htmlspecialchars($usuario["direccion"]); ?>">
                        </div>

                    </div>

            </div>

            <!-- CARD CONTRASEÑA -->

            <div class="card-seguridad">

                <h3>
                    <i class="fa-solid fa-lock"></i>
                    Seguridad
                </h3>

                <p class="ayuda-password">
                    Dejá estos campos vacíos si no querés cambiar tu contraseña.
                </p>

                <div class="fila-form">

                    <div class="campo">
                        <label>Nueva contraseña</label>
                        <input type="password" name="password">
                    </div>

                    <div class="campo">
                        <label>Confirmar contraseña</label>
                        <input type="password" name="confirmar">
                    </div>

                </div>

                <div class="perfil-botones">

                    <a href="index.php" class="btn-volver">
                        <i class="fa-solid fa-house"></i>
                        Inicio
                    </a>

                    <button type="submit" class="btn-guardar">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Guardar cambios
                    </button>

                </div>

                </form>

            </div>

        </section>

    </div>

</main>

<?php include("includes/footer.php"); ?>