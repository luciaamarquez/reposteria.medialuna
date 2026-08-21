<?php

session_start();

$conexion = new mysqli("localhost", "root", "", "medialuna");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$mensaje = "";
$tipoMensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // ==========================================
    // 1. USUARIO INGRESA EL TELÉFONO
    // ==========================================
    if (isset($_POST["telefono"])) {

        $telefono = trim($_POST["telefono"]);

        $sql = "SELECT id, nombre, telefono FROM usuarios WHERE telefono = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $telefono);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {

            $usuario = $resultado->fetch_assoc();

            // Generar código de 4 números
            $codigo = random_int(1000, 9999);

            // Guardar información en sesión
            $_SESSION["recuperacion_id"] = $usuario["id"];
            $_SESSION["recuperacion_telefono"] = $usuario["telefono"];
            $_SESSION["codigo_recuperacion"] = $codigo;
            $_SESSION["codigo_verificado"] = false;

            /*
             * POR AHORA MOSTRAMOS EL CÓDIGO PARA PROBAR.
             * Después acá podemos colocar el envío por WhatsApp.
             */

            $mensaje = "Código enviado correctamente.";
            $tipoMensaje = "exito";

        } else {

            $mensaje = "No existe ningún usuario con ese teléfono.";
            $tipoMensaje = "error";
        }

        $stmt->close();
    }


    // ==========================================
    // 2. USUARIO INGRESA EL CÓDIGO
    // ==========================================
    if (isset($_POST["codigo"])) {

        $codigoIngresado = trim($_POST["codigo"]);

        if (
            isset($_SESSION["codigo_recuperacion"]) &&
            isset($_SESSION["recuperacion_id"])
        ) {

            if ($codigoIngresado == $_SESSION["codigo_recuperacion"]) {

                $_SESSION["codigo_verificado"] = true;

                header("Location: nuevaContraseña.php");
                exit();

            } else {

                $mensaje = "El código ingresado es incorrecto.";
                $tipoMensaje = "error";
            }

        } else {

            $mensaje = "Primero tenés que solicitar un código.";
            $tipoMensaje = "error";
        }
    }
}

?>

<?php include("includes/header.php"); ?>

<main class="recuperar">

    <div class="recuperar-contenedor">

        <h2><i class="fa-solid fa-key"></i> Recuperar contraseña</h2>

        <p class="subtitulo">
            Ingresá el teléfono con el que te registraste.
        </p>

        <?php if($mensaje != ""): ?>
            <div class="mensaje <?php echo $tipoMensaje; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>


        <?php if(!isset($_SESSION["codigo_recuperacion"])): ?>

            <form method="POST">

                <div class="campo-icono">
                    <i class="fa-solid fa-phone"></i>

                    <input
                        type="text"
                        name="telefono"
                        placeholder="Teléfono"
                        required
                    >
                </div>

                <button type="submit">
                    <i class="fa-solid fa-paper-plane"></i>
                    Enviar código
                </button>

            </form>

        <?php else: ?>

            <p class="subtitulo">
                Ingresá el código de 4 dígitos enviado a tu teléfono.
            </p>

            <!-- Solo para pruebas -->
            <div class="codigo-demo">
                Código de prueba:
                <strong><?php echo $_SESSION["codigo_recuperacion"]; ?></strong>
            </div>

            <form method="POST">

                <div class="campo-icono">
                    <i class="fa-solid fa-shield-halved"></i>

                    <input
                        type="text"
                        name="codigo"
                        maxlength="4"
                        placeholder="Código de verificación"
                        required
                    >
                </div>

                <button type="submit">
                    <i class="fa-solid fa-check"></i>
                    Verificar código
                </button>

            </form>

        <?php endif; ?>

    </div>

</main>

<?php include("includes/footer.php"); ?>
```
