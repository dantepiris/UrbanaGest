<?php

$vars = ["MSG_ERROR" => ""];
if (isset($_POST['btn__login'])) {

    $usuario_ = new Users();

    unset($_POST['btn__login']);

    // Ejecuta el inicio de sesión con los datos del formulario
    $response = $usuario_->login($_POST);

    // Verificar si el login fue exitoso
    if ($response["errno"] == 200) {
        // Inicia la sesión si aún no está activa
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Guardar la información del usuario en la sesión
        $_SESSION['huertaenred']['user'] = [
            'email' => $_POST['txt_email'],
            'pass' => $_POST['txt_contraseña']
        ];

        // Comprobar si el email y la contraseña son las especificadas
        if ($_POST['txt_email'] == 'urbangest.services@gmail.com' && $_POST['txt_contraseña'] == 'operador_urbanagest') {
            // Redirigir a la vista de operador
            header("Location: operador");
        } elseif ($_POST['txt_email'] == 'urbanagest@gmail.com' && $_POST['txt_contraseña'] == 'urbanagest_admin') {
            // Redirigir a la vista de administrador
            header("Location: paneladmin");
        } else {
            // Redirigir al panel en caso de otras credenciales
            header("Location: panel");
        }
    } else {
        // Cargar el mensaje de error en caso de que no se pueda iniciar sesión
        $vars = ["MSG_ERROR" => $response["error"]];
    }
}
// Carga la vista
$tpl = new Pork("login");

/* Carga en la plantilla el nombre de la sección */
$tpl->setVars($vars);

// Imprime la vista en la página  
$tpl->print();

?>

