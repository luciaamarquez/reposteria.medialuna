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

        <h2>Recuperar Contraseña</h2>

        <?php if ($mensaje != ""): ?>

            <p class="<?php echo $tipoMensaje; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </p>

        <?php endif; ?>

        <form method="POST">

            <input
                type="text"
                name="telefono"
                placeholder="Teléfono"
                required
            >

            <button type="submit">
                ENVIAR CÓDIGO
            </button>

        </form>

    </div>


    <div class="codigo-contenedor">

        <p>Ingresá el código enviado por SMS/WhatsApp</p>

        <?php if (isset($_SESSION["codigo_recuperacion"])): ?>

            <!-- SOLO PARA PRUEBAS -->
            <p>
                <strong>
                    Código de prueba:
                    <?php echo $_SESSION["codigo_recuperacion"]; ?>
                </strong>
            </p>

        <?php endif; ?>


        <form method="POST">

            <div class="codigo-inputs">

                <input
                    type="text"
                    name="codigo"
                    maxlength="4"
                    placeholder="Código"
                    required
                >

            </div>

            <button type="submit">
                VERIFICAR
            </button>

        </form>

    </div>

</main>

<?php include("includes/footer.php"); ?>
```
