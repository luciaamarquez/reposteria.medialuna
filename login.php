<?php
session_start();
require_once "conexion.php";

$errorLogin = "";
$campoInvalido = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $consulta = $conexion->prepare("SELECT id, nombre, rol, password FROM usuarios WHERE email = ?");
    $consulta->bind_param("s", $email);
    $consulta->execute();
    $usuario = $consulta->get_result()->fetch_assoc();

    if ($usuario && password_verify($password, $usuario["password"])) {
        session_regenerate_id(true);
        $_SESSION["id"] = $usuario["id"];
        $_SESSION["nombre"] = $usuario["nombre"];
        $_SESSION["rol"] = $usuario["rol"];
        header("Location: " . ($usuario["rol"] === "admin" ? "admin/index.php" : "index.php"));
        exit();
    }

    $errorLogin = "El correo o la contraseña son incorrectos.";
    $campoInvalido = $usuario ? "password" : "email";
}

include("includes/header.php");
?>

<main class="login">

    <div class="login-contenedor">

        <h2>Iniciar sesión</h2>

<form action="login.php" method="POST">
            <?php if ($errorLogin !== ""): ?>
                <p class="mensaje-login-error" role="alert"><?= htmlspecialchars($errorLogin) ?></p>
            <?php endif; ?>
            <input
                type="email"
                name="email"
                placeholder="Email"
                class="<?= $campoInvalido === "email" ? "campo-invalido" : "" ?>"
                aria-invalid="<?= $campoInvalido === "email" ? "true" : "false" ?>"
                value="<?= htmlspecialchars($_POST["email"] ?? "") ?>"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Contraseña"
                class="<?= $campoInvalido === "password" ? "campo-invalido" : "" ?>"
                aria-invalid="<?= $campoInvalido === "password" ? "true" : "false" ?>"
                required
            >

            <button type="submit">
                INGRESAR
            </button>

        </form>

        <details class="admin-demo">
            <summary>
                <i class="fa-solid fa-user-shield"></i>
            </summary>

            <div class="admin-demo-info">
                <h4>Administrador</h4>

                <p><strong>Email:</strong> admin@dulce.com</p>
                <p><strong>Contraseña:</strong> medialuna0206</p>
            </div>
        </details>

        <p class="registro">
            ¿No tenés cuenta? 
            <a href="registro.php">Registrate acá <i class="fa-solid fa-user-plus"></i></a>
        </p>

    </div>

</main>

<?php include("includes/footer.php"); ?>