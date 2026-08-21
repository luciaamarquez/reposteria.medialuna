<?php include("includes/header.php"); ?>

<main class="registro">

    <div class="registro-contenedor">

        <h2>Crear cuenta</h2>

        <form action="registrar.php" method="POST">

            <div class="fila-form">
                <div class="campo-icono">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="nombre" placeholder="Nombre *" required>
                </div>

                <div class="campo-icono">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="apellido" placeholder="Apellido *" required>
                </div>
            </div>

            <div class="campo-icono">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" name="email" placeholder="Email *" required>
            </div>

            <div class="fila-form">
                <div class="campo-icono">
                    <i class="fa-solid fa-phone"></i>
                    <input type="text" name="telefono" placeholder="Teléfono">
                </div>

                <div class="campo-icono">
                    <i class="fa-solid fa-location-dot"></i>
                    <input type="text" name="direccion" placeholder="Dirección">
                </div>
            </div>

            <div class="fila-form">
                <div class="campo-icono">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Contraseña *" required>
                </div>

                <div class="campo-icono">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="confirmar" placeholder="Confirmar contraseña *" required>
                </div>
            </div>

            <button type="submit">CREAR CUENTA</button>

            </form>

            <p class="registro-login">
            ¿Ya tenés cuenta?<br>
            <a href="login.php">
                Iniciá sesión acá <i class="fa-solid fa-right-to-bracket"></i>
            </a>
            </p>

    </div>

</main>

<?php include("includes/footer.php"); ?>