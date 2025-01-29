<?php
// Inicia la sesión solo si no está activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si la sesión contiene el usuario
if (isset($_SESSION['huertaenred']['user']) && !empty($_SESSION['huertaenred']['user'])) {
    $usuario = $_SESSION['huertaenred']['user'];
    $vars = [
        "PROJECT_SECTION" => "perfil",
        "txt_email" => isset($usuario['email']) ? $usuario['email'] : 'No disponible',
        "txt_contraseña" => isset($usuario['pass']) ? $usuario['pass'] : 'No disponible'
    ];

    // Carga la vista
    $tpl = new Pork("perfil");

    // Carga las variables en la vista
    $tpl->setVars($vars);

    // Imprime la vista en la página
    $tpl->print();
} else {
    echo 'No se encontró información del usuario en la sesión. Por favor, inicia sesión nuevamente.';
    // Aquí podrías redirigir al usuario a la página de inicio de sesión si es necesario
    exit;
}

