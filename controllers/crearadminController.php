<?php 

if(isset($_POST['btn_publicacion'])){

		$line = new Users();
		$line->noticia($_POST);

		 

	}

	
	// Carga la vista
	$tpl = new Pork("crearadmin");



	// imprime la vista en la página
	$tpl->print();
 ?>