<?php 


if(isset($_POST['btn_evento'])){

		$line = new Users();
		$line->eventos($_POST); 

	}


	// Carga la vista
	$tpl = new Pork("creareventos");



	// imprime la vista en la página
	$tpl->print();

 ?>


