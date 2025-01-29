<?php 

	 $noticia = explode("=",$_SERVER['REQUEST_URI']); 

	// Carga la vista
	$tpl = new Pork("noticia");



	$tpl->setVars(["TOKEN"=>$noticia[1]]);
	// imprime la vista en la página
	$tpl->print();

 ?>


