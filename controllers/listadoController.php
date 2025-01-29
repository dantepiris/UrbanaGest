<?php 
	
	// Carga la vista
	$tpl = new Pork("listado");

	/*< Carga en la plantilla el nombre de la sección */
	$tpl->setVars(["PROJECT_SECTION" => "Listado"]);

	// imprime la vista en la página
	$tpl->print();
 ?>