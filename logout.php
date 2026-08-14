<?php
session_start();

// Destruir la sesión
session_unset();
session_destroy();

// Redirigir a index.php
header("Location: index.php");
exit();
?>