<?php include("includes/header.php"); ?>

<main class="registro">

    <div class="registro-contenedor">

        <h2>Crear cuenta</h2>

<form action="registrar.php" method="POST">
            <div class="fila-form">

                <input type="text" name="nombre" placeholder="Nombre *" required>

                <input type="text" name="apellido" placeholder="Apellido *" required>

            </div>

            <input type="email" name="email" placeholder="Email *" required>

            <div class="fila-form">

                <input type="text" name="telefono" placeholder="Teléfono">

                <input type="text" name="direccion" placeholder="Dirección">

            </div>

            <div class="fila-form">

                <input type="password" name="password" placeholder="Contraseña *" required>

                <input type="password" name="confirmar" placeholder="Confirmar Contraseña *" required>

            </div>

            <button type="submit">
                CREAR CUENTA
            </button>

        </form>

        <p class="registro-login">

            ¿Ya tenés cuenta?

            <a href="login.php">Iniciá sesión acá</a>

        </p>

    </div>

</main>

<?php include("includes/footer.php"); ?>