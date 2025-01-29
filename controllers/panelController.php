<?php
// Verificar si la sesión ya está activa antes de llamar a session_start
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['huertaenred']['user'])) {
    // Si no hay sesión activa, redirigir al login
    header('Location: login');
    exit(); // Importante para detener el script después de redirigir
}

// Si el usuario está logueado, preparar las variables para la vista
$vars = [
    "PROJECT_SECTION" => "Panel",
    "email" => $_SESSION['huertaenred']['user']['email']
];


// Cargar la vista
$tpl = new Pork("panel");
$tpl->setVars($vars);

// Imprimir la vista en la página
$tpl->print();
?>
