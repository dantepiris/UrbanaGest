<?php 

	// variables para la vista
	$vars = ["PROJECT_SECTION" => "Registro", "MSG_ERROR" => ""];

	// si se presiono el botón de registrarse
	if(isset($_POST['btn__register'])){

		// instancia la clase Users
		$usuario = new Users();

		// borra el botón del formulario
		unset($_POST["btn__register"]);

		// ejecuta el registro con los datos del formulario
		$response = $usuario->register($_POST);

		// en caso de que el registro sea correcto
		if($response["errno"]==200 || $response["errno"]==201){

			// carga el objeto user en el vector de sesion
			 $_SESSION['huertaenred']['user'] = $usuario;

			// redirecciona al perfil de usuario
			header("Location: revisa");
		}

		// carga el mensaje de error en caso de que no se pueda registrar el nuevo usuario
		$vars = ["MSG_ERROR" => $response["error"]];
	} 
	
	// Carga la vista
	$tpl = new Pork("register");

	// carga las variables en la vista
	$tpl->setVars($vars);

	// imprime la vista en la página
	$tpl->print();
 ?>