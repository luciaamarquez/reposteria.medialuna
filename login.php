<?php
include("includes/header.php");
?>

<main>

    <section class="login">

        <div class="login-contenedor">

            <h2>Iniciar sesión</h2>

            <form action="" method="POST">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    placeholder="Ingrese su email"
                    required
                >

                <label>Contraseña</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Ingrese su contraseña"
                    required
                >

                <button type="submit">

                    INGRESAR

                </button>

            </form>

            <a href="registro.php">
                ¿No tenés cuenta? Registrate acá
            </a>

            <br><br>

            <a href="#">
                ¿Olvidaste tu contraseña?
            </a>

        </div>

    </section>

</main>

<?php
include("includes/footer.php");
?>