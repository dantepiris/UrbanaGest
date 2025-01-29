<?php 

//~0uwzmN=Og6.Id<y

	/**
	 * 
	 * En el caso de utilizar el archivo .env
	 * 
	 * $host = $_ENV["HOST"];
	 * otra forma
	 * define("HOST", $_ENV["HOST"]);
	 * 
	 * */

	/*< Sección para ejecutar la app en modo release o debug*/
	define("RELEASE", 0); // Ejecución
	define("DEBUG", 1); // Desarrollo

	/*< Para modo debug o release cambiar */
	define("MODE", DEBUG);

	define("TOKEN", "C5xZAVJGKkhwQr8hiPhmlh32rjDIBc0tbGuY5fO1MtMAe2UZsvKll2ycOUcwwrXf");

	// Token unico de acceso a la apliacion (varia para cada cliente)
	$_ENV['TOKEN_APP'] = TOKEN;

	// variables de entorno para nombres de la aplicacion y rutas
	$_ENV["PROJECT_NAME"] = "urbanagest";
	$_ENV["PROJECT_DESCRIPTION"] = "Somos urbanagest sitio de autogestion";
	$_ENV["PROJECT_AUTHOR"] = "urbanagest";
	$_ENV["PROJECT_AUTHOR_CONTACT"] = "urbanagest.com";
	$_ENV["PROJECT_WEB"] = "urbanagest.tecnica3.com.ar";
	// $_ENV["API_URL"] = "";
	$_ENV["PROJECT_KEYWORDS"] = "lddadadsaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";

	$_ENV["PROJECT_MODE"] = MODE ? "?v=".mt_rand(0, 9999) : "";

	// variables de entorno para acceso a la base de datos
	$_ENV["HOST"] = "localhost";
	$_ENV["USER"] = "urbanagest";
	$_ENV["PASS"] = "NhAhqnMpJiC!2QO";
	$_ENV["DB"] = "urbanagest";

	// variables de entorno para acceso al correo electrónico
	$_ENV["MAILER_REMITENTE"] = 'urbanagest@gmail.com'; // <=== correo de la app
	$_ENV["MAILER_NOMBRE"] = 'Urbanagest'; // <=== Nombre que se muestra al destinatario
	$_ENV["MAILER_PASSWORD"] = 'ikfg sqgf xksy hmza'; // <=== token

	$_ENV["MAILER_HOST"] = 'smtp.gmail.com';
	$_ENV["MAILER_PORT"] = '587';
	$_ENV["MAILER_SMTP_AUTH"] = true;
	$_ENV["MAILER_SMTP_SECURE"] = 'tls';


 ?>