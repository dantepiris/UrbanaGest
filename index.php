	<?php 

	/*< incluimos las variables de entorno */
	include_once 'env.php';

	include 'lib/php-mailer/Mailer/src/PHPMailer.php';
	include 'lib/php-mailer/Mailer/src/SMTP.php';
	include 'lib/php-mailer/Mailer/src/Exception.php';

	// carga de modelos para que esten disponibles en todos los controladores
	include_once 'models/Users.php';

	// inicia o continua la sesión
	session_start();
	

	# index es un Router el cual redirecciona a las distintas secciones
	
	// Carga del motor de plantillas
	include_once 'lib/Pork/Pork.php';

	// por defecto seccion es landing
	$seccion = "landing";

	// slug tiene valor
	if($_GET['slug']!="")
		$seccion = $_GET['slug'];

	// verificamos que exista el controlador
	if(!file_exists('controllers/'.$seccion.'Controller.php')){
		// si no existe el controlador lo llevamos al controlador de error 404
		$seccion = "error404";
	}
   // var_dump($_SESSION);
	// === firewall

	// listas de acceso por tipo de usuario
	$seccion_anonimo = ["landing","contacto","panel", "login", "register","verify","revisa","eventos","noticias","noticia"];

	$seccion_operador=["landing","contacto", "login", "register","verify","revisa","operador"];

	$seccion_admin=["landing","contacto", "login", "register","verify","revisa","paneladmin","crearadmin","vereventos","creareventos","editaradmin"];

	$seccion_usuario = ["logout", "abandonar","panel","mostrarboleta","perfil","login","register","revisa","landing", "boleta","operador","turnosdni","eventos","noticias","noticia"];	

    $aux=0;

	// si el usuario esta logueado
	if(isset($_SESSION['huertaenred'])){
		// recorro la lista de secciones no permitidas
		foreach ($seccion_usuario as $key => $value) {
			if($value==$seccion){
				$aux=1;
			}
		}
			foreach ($seccion_operador as $key => $value) {
			if($value==$seccion){
				$aux=1;
			}
		}
		foreach ($seccion_admin as $key => $value) {
			if($value==$seccion){
				$aux=1;
			}
		}
	}else{ // si no hay usuario logueado

		// recorro la lista de secciones no permitidas
		foreach ($seccion_anonimo as $key => $value) {
			if($value==$seccion){
				$aux=1;
			}
		}
	}
 if ($aux==0) {
 	$seccion = "error404";

 }
	// === fin firewall


	// Carga del controlador
	include_once 'controllers/'.$seccion.'Controller.php';

 ?>