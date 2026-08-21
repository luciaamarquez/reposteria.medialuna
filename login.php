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

        <a href="recuperar.php" class="olvide">
            ¿Olvidaste tu contraseña?
        </a>

    </div>

</main>

<?php include("includes/footer.php"); ?>