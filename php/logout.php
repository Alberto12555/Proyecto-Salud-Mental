<?php
session_start(); // Inicia la sesión si no está iniciada

// Destruye todas las variables de sesión
session_unset();

// Destruye la sesión
session_destroy();

// Redirige al usuario a la página de inicio
header("Location: ../index.html");
exit();