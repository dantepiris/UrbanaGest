<?php 

	// Carga la vista
	$tpl = new Pork("error404");

	/*< Carga en la plantilla el nombre de la sección */
	$tpl->setVars(["PROJECT_SECTION" => "Error"]);

	// imprime la vista en la página
	$tpl->print();
 ?>