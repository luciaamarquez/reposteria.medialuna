<?php include("includes/header.php"); ?>

<main class="recuperar">

    <div class="recuperar-contenedor">

        <h2>Recuperar Contraseña</h2>

        <form action="#" method="POST">

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

        <p>Ingresá el código enviado por SMS</p>

        <div class="codigo-inputs">

            <input type="text" maxlength="1">
            <input type="text" maxlength="1">
            <input type="text" maxlength="1">
            <input type="text" maxlength="1">

        </div>

        <button>
            VERIFICAR
        </button>

    </div>

</main>

<?php include("includes/footer.php"); ?>