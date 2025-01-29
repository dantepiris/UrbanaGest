<?php 


if(isset($_POST['btn_suscriptor'])){
  
		$line = new Users();
		$line->suscriptores($_POST); 	

	}

	// Carga la vista
	$tpl = new Pork("eventos");



	// imprime la vista en la página
	$tpl->print();
 ?>