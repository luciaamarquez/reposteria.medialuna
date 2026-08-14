<?php

session_start();

/*
 * Comprobar que el usuario realmente
 * verificó el código.
 */

if (
    !isset($_SESSION["codigo_verificado"]) ||
    $_SESSION["codigo_verificado"] !== true ||
    !isset($_SESSION["recuperacion_id"])
) {

    header("Location: recuperar.php");
    exit();
}


// Conexión a la base de datos

$conexion = new mysqli("localhost", "root", "", "medialuna");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}


$mensaje = "";


// ==========================================
// ACTUALIZAR CONTRASEÑA
// ==========================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $password = $_POST["password"] ?? "";
    $passwordConfirmacion = $_POST["password_confirmacion"] ?? "";


    // Comprobar que no esté vacía

    if (empty($password)) {

        $mensaje = "Ingresá una contraseña.";

    }

    // Comprobar que coincidan

    elseif ($password !== $passwordConfirmacion) {

        $mensaje = "Las contraseñas no coinciden.";

    }

    // Comprobar longitud

    elseif (strlen($password) < 6) {

        $mensaje = "La contraseña debe tener al menos 6 caracteres.";

    }

    else {

        /*
         * Convertimos la contraseña en un HASH.
         * Nunca guardamos la contraseña directamente.
         */

        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );


        $idUsuario = $_SESSION["recuperacion_id"];


        // Actualizar contraseña

        $sql = "UPDATE usuarios SET password = ? WHERE id = ?";

        $stmt = $conexion->prepare($sql);

        $stmt->bind_param(
            "si",
            $passwordHash,
            $idUsuario
        );


        if ($stmt->execute()) {

            /*
             * Recuperación terminada.
             * Eliminamos los datos temporales.
             */

            unset($_SESSION["codigo_recuperacion"]);
            unset($_SESSION["recuperacion_id"]);
            unset($_SESSION["recuperacion_telefono"]);
            unset($_SESSION["codigo_verificado"]);


            // Volver al login

            header("Location: login.php");
            exit();

        } else {

            $mensaje = "No se pudo actualizar la contraseña.";
        }


        $stmt->close();
    }
}

?>

<?php include("includes/header.php"); ?>

<main class="nueva-password">

    <div class="verificado">

        <p>Código verificado</p>

        <i class="fa-solid fa-circle-check"></i>

    </div>


    <div class="nueva-password-contenedor">

        <h2>Ingresá nueva contraseña</h2>


        <?php if ($mensaje != ""): ?>

            <p class="error">
                <?php echo htmlspecialchars($mensaje); ?>
            </p>

        <?php endif; ?>


        <form method="POST">

            <input
                type="password"
                name="password"
                placeholder="Nueva contraseña"
                minlength="6"
                required
            >


            <input
                type="password"
                name="password_confirmacion"
                placeholder="Repetir nueva contraseña"
                minlength="6"
                required
            >


            <button type="submit">
                ACTUALIZAR CONTRASEÑA
            </button>

        </form>

    </div>

</main>

<?php include("includes/footer.php"); ?>
```
