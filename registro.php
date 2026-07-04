<?php
include("includes/header.php");
?>

<main>

    <section class="registro">

        <div class="registro-contenedor">

            <h2>Crear Cuenta</h2>

            <form action="" method="POST">

                <label>Nombre</label>
                <input type="text" name="nombre" required>

                <label>Apellido</label>
                <input type="text" name="apellido" required>

                <label>Email</label>
                <input type="email" name="email" required>

                <label>Teléfono</label>
                <input type="text" name="telefono">

                <label>Dirección</label>
                <input type="text" name="direccion">

                <label>Contraseña</label>
                <input type="password" name="password" required>

                <label>Confirmar contraseña</label>
                <input type="password" name="confirmar" required>

                <button type="submit">
                    CREAR CUENTA
                </button>

            </form>

            <p>
                ¿Ya tenés una cuenta?
                <a href="login.php">Iniciá sesión</a>
            </p>

        </div>

    </section>

</main>

<?php
include("includes/footer.php");
?>