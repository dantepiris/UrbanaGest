<?php 


if(isset($_POST['btn_turno'])){
  
		$line = new Users();
		$line->turnosasignados($_POST); 
		

			

	}

	// Carga la vista
	$tpl = new Pork("turnosdni");



	// imprime la vista en la página
	$tpl->print();

 ?>


