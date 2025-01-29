<?php 

	/*< instancia la clase Users para hacer la verificación*/
	$user = new Users();

	/*< recupera el token enviado por GET*/
	// $get["token"] = (str_replace("/verify?token=", "", $_SERVER["REQUEST_URI"]));





	// /*< testea el token (se envia como un form)*/
	// $user->verify($get);
	
	// /*< redirección a perfil para que lo complete*/
	header("Location: login");


 ?>



