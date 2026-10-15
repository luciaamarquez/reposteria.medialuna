<?php

session_start();

$conexion = new mysqli("localhost", "root", "", "medialuna");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$mensaje = "";
$tipoMensaje = "";

if (($_GET["reiniciar"] ?? "") === "1") {
    unset(
        $_SESSION["codigo_recuperacion"],
        $_SESSION["codigo_expira"],
        $_SESSION["codigo_intentos"],
        $_SESSION["recuperacion_id"],
        $_SESSION["recuperacion_telefono"],
        $_SESSION["codigo_verificado"]
    );
}

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

            try {
                $phoneNumberId = getenv("WHATSAPP_PHONE_NUMBER_ID");
                $accessToken = getenv("WHATSAPP_ACCESS_TOKEN");
                $apiVersion = getenv("WHATSAPP_API_VERSION") ?: "v23.0";
                $templateName = getenv("WHATSAPP_TEMPLATE_NAME");

                if (
                    $phoneNumberId === false || $phoneNumberId === "" ||
                    $accessToken === false || $accessToken === "" ||
                    $templateName === false || $templateName === ""
                ) {
                    throw new RuntimeException("Falta configurar Meta WhatsApp Cloud API y su plantilla.");
                }

                $destino = preg_replace("/\D+/", "", $usuario["telefono"]);
                if (strlen($destino) === 10) {
                    $destino = "549" . $destino;
                } elseif (strlen($destino) === 11 && str_starts_with($destino, "0")) {
                    $destino = "549" . substr($destino, 1);
                }

                if (strlen($destino) < 11) {
                    throw new RuntimeException("El teléfono guardado no incluye un código de país válido.");
                }

                $codigo = (string) random_int(1000, 9999);
                $payload = [
                    "messaging_product" => "whatsapp",
                    "to" => $destino,
                    "type" => "template",
                    "template" => [
                        "name" => $templateName,
                        "language" => ["code" => "es_AR"],
                        "components" => [[
                            "type" => "body",
                            "parameters" => [[
                                "type" => "text",
                                "text" => $codigo,
                            ]],
                        ]],
                    ],
                ];

                $curl = curl_init(
                    "https://graph.facebook.com/" . rawurlencode($apiVersion) . "/" .
                    rawurlencode($phoneNumberId) . "/messages"
                );
                curl_setopt_array($curl, [
                    CURLOPT_POST => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTPHEADER => [
                        "Authorization: Bearer " . $accessToken,
                        "Content-Type: application/json",
                    ],
                    CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                    CURLOPT_TIMEOUT => 20,
                ]);

                $respuesta = curl_exec($curl);
                if ($respuesta === false) {
                    $errorCurl = curl_error($curl);
                    curl_close($curl);
                    throw new RuntimeException("No se pudo conectar con WhatsApp: " . $errorCurl);
                }

                $estadoHttp = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);
                $datosRespuesta = json_decode($respuesta, true);

                if ($estadoHttp < 200 || $estadoHttp >= 300) {
                    $errorWhatsApp = $datosRespuesta["error"]["message"] ?? "Error HTTP " . $estadoHttp;
                    throw new RuntimeException("WhatsApp rechazó el envío: " . $errorWhatsApp);
                }

                $_SESSION["recuperacion_id"] = $usuario["id"];
                $_SESSION["recuperacion_telefono"] = $usuario["telefono"];
                $_SESSION["codigo_recuperacion"] = $codigo;
                $_SESSION["codigo_expira"] = time() + 600;
                $_SESSION["codigo_intentos"] = 0;
                $_SESSION["codigo_verificado"] = false;
                $mensaje = "Te enviamos el código por WhatsApp. Vence en 10 minutos.";
                $tipoMensaje = "exito";
            } catch (Throwable $e) {
                error_log("Error enviando código de recuperación por WhatsApp: " . $e->getMessage());
                $mensaje = "No pudimos enviar el código. Revisá la configuración y el número de teléfono.";
                $tipoMensaje = "error";
            }

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

            if (
                !isset($_SESSION["codigo_expira"]) ||
                time() > (int) $_SESSION["codigo_expira"]
            ) {
                unset(
                    $_SESSION["codigo_recuperacion"],
                    $_SESSION["codigo_expira"],
                    $_SESSION["codigo_intentos"],
                    $_SESSION["recuperacion_id"],
                    $_SESSION["recuperacion_telefono"]
                );
                $mensaje = "El código venció. Solicitá uno nuevo.";
                $tipoMensaje = "error";
            } elseif ((int) ($_SESSION["codigo_intentos"] ?? 0) >= 5) {
                unset(
                    $_SESSION["codigo_recuperacion"],
                    $_SESSION["codigo_expira"],
                    $_SESSION["codigo_intentos"],
                    $_SESSION["recuperacion_id"],
                    $_SESSION["recuperacion_telefono"]
                );
                $mensaje = "Se agotaron los intentos. Solicitá un código nuevo.";
                $tipoMensaje = "error";
            } elseif (hash_equals((string) $_SESSION["codigo_recuperacion"], $codigoIngresado)) {

                $_SESSION["codigo_verificado"] = true;

                header("Location: nuevaContraseña.php");
                exit();

            } else {

                $_SESSION["codigo_intentos"] = (int) ($_SESSION["codigo_intentos"] ?? 0) + 1;
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

            <p class="subtitulo">
                Si no recibiste el mensaje, podés <a href="recuperar.php?reiniciar=1">solicitar otro código</a>.
            </p>

        <?php endif; ?>

        <p class="volver-paginas"><a href="login.php">← Volver a iniciar sesión</a></p>
    </div>

</main>

<?php include("includes/footer.php"); ?>
```
