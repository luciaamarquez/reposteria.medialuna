<?php include("includes/header.php"); ?>

<main class="login">

    <div class="login-contenedor">

        <h2>Iniciar sesión</h2>

<form action="login_usuarios.php" method="POST">
            <input
                type="email"
                name="email"
                placeholder="Email"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Contraseña"
                required
            >

            <button type="submit">
                INGRESAR
            </button>

        </form>

        <p class="registro">
            ¿No tenés cuenta?
            <a href="registro.php">Registrate acá</a>
        </p>

        <a href="recuperar.php" class="olvide">
            ¿Olvidaste tu contraseña?
        </a>

    </div>

</main>

<?php include("includes/footer.php"); ?>