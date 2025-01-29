<?php 

	if(isset($_POST['btn_mensaje'])){

		$line = new Users();
		$line->reclamo($_POST); 

	}

	// Carga la vista
	$tpl = new Pork("contacto");


	// imprime la vista en la página
	$tpl->print();

?>