<?php 

	// carga la vista
	$tpl = new Pork("landing");

	// creamos un usuario
	$usuario = new Users();


	// imprime la vista en la página
	$tpl->print();

 ?>