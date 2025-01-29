<?php 

// Obtener el token de la URL
$token = explode("=", $_SERVER["REQUEST_URI"])[1];

// Incluir el modelo Users
require_once("models/Users.php");
$usersModel = new Users();

// Buscar la noticia por su token
$noticia = $usersModel->obtenerNoticiaPorToken($token);

// Si se envió el formulario, actualizar la noticia
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo_txt'];
     $fecha = $_POST['fecha_txt'];
     $tipo = $_POST['optionSelect'];
    $imgUrl = $_POST['img_url'];
    $informacion = $_POST['mensaje_txt'];

    // Actualizar la noticia en la base de datos
    $usersModel->actualizarNoticia($token, $titulo,$fecha,$tipo, $imgUrl, $informacion);

    // Redirigir después de guardar
    header("Location: paneladmin"); // Redirige a la página principal del admin
    exit();
}

// Cargar la vista
$tpl = new Pork("editaradmin");

// Pasar los datos existentes a la vista
$tpl->setVars([
    "TOKEN" => $token,
    "TITULO" => $noticia[0]['titulo'],
    "FECHA" => $noticia[0]['fecha'], 
      "TIPO" => $noticia[0]['tipo_novedad'],  // Aquí va el título actual de la noticia
    "IMG_URL" => $noticia[0]['img'], // URL actual de la imagen
    "MENSAJE" => $noticia[0]['informacion']  // Mensaje actual
]);

// Imprimir la vista en la página
$tpl->print();
?>
