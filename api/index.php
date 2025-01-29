<?php

	/**
	* @file index.php
	* @brief API Restful para el proyecto HuertaEnRed
	* @author Matias Leonardo Baez
	* @date 2024
	* @contact elmattprofe@gmail.com
	*/

	/*< inicia o continua la sesion*/
	session_start();

	/*< la respuesta es un JSON*/
	header("Content-Type: application/json");

	/*< incluimos las variables de entorno */
	include_once '../env.php';

	/*< incluimos las librerias para el envio de email*/
	include '../lib/php-mailer/Mailer/src/PHPMailer.php';
	include '../lib/php-mailer/Mailer/src/SMTP.php';
	include '../lib/php-mailer/Mailer/src/Exception.php';

	// metodo por el cual invocaron a la api ["REQUEST_METHOD"]
	// muestra el contenido de las variables ["QUERY_STRING"]

	/*< limpia petición a la API quitandole /api/ y luego separa en un vector */
	$request_uri =explode("/",str_replace("/api/" ,"", $_SERVER["REQUEST_URI"]));


	// si no existe la posición 0 correspondiente al modelo
	if(!isset($request_uri[0])){
		echo json_encode(["errno" => 404, "error" => "Falta especificar el modelo al cual acceder"]);
		exit();
	}

	if(!isset($request_uri[1])){
		echo json_encode(["errno" => 404, "error" => "Falta especificar el metodo al cual acceder"]);
		exit();
	}

	/*< Normalización del valor de modelo, todo a minúscula y la primer letra en mayúscula */
	$modelo = ucfirst((strtolower($request_uri[0
	])));
    
	/*< valida que exista el archivo correspondiente al modelo */
	if(!file_exists('../models/'.$modelo.'.php')){
		echo json_encode(["errno" => 404, "error" => "Modelo no existente"]);
		exit();
	}
	
	/*< incluye el modelo*/
	include_once '../models/'.$modelo.'.php';

	/*< Instancia la clase en un objeto */
	$object = new $modelo();

	/*< Si no existe la posición 1 correspondiente al método de la clase */
	if(!isset($request_uri[0])){
		echo json_encode(["errno" => 404, "error" => "Falta especificar el modelo a utilizar"]);
		exit();
	}
	
	/*< valida que exista el metodo dentro del objeto*/
	if(!method_exists($object,$request_uri[1])){
		echo json_encode(["errno" => 404, "error" => "No existe el metodo"]);
		exit();
	}

	/*< se pasa metodo del vector de GET a una variable para que sea más comodo */
	$method = $request_uri[1];

	/*< Se analiza el method http para obtener las variables del vector adecuado*/
	switch ($_SERVER['REQUEST_METHOD']) {
		case 'DELETE': // variables viajan por la url
		case 'GET':
				$params = $_GET;
			break;

		case 'POST': // variables viajan dentro de un formulario -> body
				$params = $_POST;
			break;

		case 'PUT': // variables viajan dentro del body
				parse_str(file_get_contents("php://input"), $_PUT);
				$params = $_PUT; // actualizar dentro de la api
			break;
	}

	/*< ejecuta el método de la clase con los parámetros */
	$response = $object->$method($params);

	/*< transforma la respuesta en un JSON y la pinta en el cuerpo de la página */
	echo json_encode($response);