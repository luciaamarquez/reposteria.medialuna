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
    <div class="perfil-contenedor">
        <h2>Mi perfil</h2>
        <p class="perfil-subtitulo">
            Editá tus datos personales
        </p>

        <?php if (isset($_GET["mensaje"])): ?>

            <div class="mensaje-exito">
                <?php echo htmlspecialchars($_GET["mensaje"]); ?>
            </div>

        <?php endif; ?>


        <?php if (isset($_GET["error"])): ?>

            <div class="mensaje-error">
                <?php echo htmlspecialchars($_GET["error"]); ?>
            </div>

        <?php endif; ?>


        <form action="actualizar_perfil.php" method="POST">

            <!-- NOMBRE Y APELLIDO -->

            <div class="fila-form">
                <div>
                    <label>Nombre</label></br>
                    <input
                        type="text"
                        name="nombre"
                        value="<?php echo htmlspecialchars($usuario["nombre"]); ?>"
                        required>
                </div>

                <div>
                    <label>Apellido</label></br>
                    <input
                        type="text"
                        name="apellido"
                        value="<?php echo htmlspecialchars($usuario["apellido"]); ?>"
                        required> 
                </div>
            </div>


            <!-- EMAIL -->

            <label>Email</label></br>

            <input
                type="email"
                name="email"
                value="<?php echo htmlspecialchars($usuario["email"]); ?>"
                required
            ></br></br> 


            <!-- TELEFONO Y DIRECCION -->

            <div class="fila-form">

                <div>
                    <label>Teléfono</label></br>

                    <input
                        type="text"
                        name="telefono"
                        value="<?php echo htmlspecialchars($usuario["telefono"]); ?>"
                    >
                </div>


                <div>
                    <label>Dirección</label></br>

                    <input
                        type="text"
                        name="direccion"
                        value="<?php echo htmlspecialchars($usuario["direccion"]); ?>"
                    >
                </div>

            </div>


            <hr>


            <!-- CONTRASEÑA -->

            <h3>Cambiar contraseña</h3>

            <p class="ayuda-password">
                Dejá estos campos vacíos si no querés cambiar tu contraseña.
            </p>


            <div class="fila-form">

                <div>
                    <label>Nueva contraseña</label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Nueva contraseña"
                    >
                </div>


                <div>
                    <label>Confirmar contraseña</label>

                    <input
                        type="password"
                        name="confirmar"
                        placeholder="Confirmar contraseña"
                    >
                </div>

            </div>


            <!-- GUARDAR -->
        </form>
        <div class="botones-perfil">

    <button class="btn-guardar-cambios" type="submit">
        <i class="fa-solid fa-floppy-disk"></i>
        GUARDAR CAMBIOS
    </button>

    <a href="logout.php" class="btn-cerrar-sesion">
        <i class="fa-solid fa-right-from-bracket"></i>
        CERRAR SESIÓN
    </a>

    <a href="index.php" class="volver">
        ← Volver al inicio
    </a>

</div>
    </div>

</main>

<?php include("includes/footer.php"); ?>