<?php

	/**
	* @file Users.php
	* @brief Implementación de la clase Users para el manejo de usuario.
	* @author Matias Leonardo Baez
	* @date 2024
	* @contact elmattprofe@gmail.com
	*/
	
	// Incluimos la clase que conecta a la base de datos
	include_once 'DBAbstract.php';

	/*< incluimos la clase para enviar correo electrónico*/
	include_once 'Mailer.php';
	
	/**
	 * 
	 * Clase para trabajar con la tabla de usuarios
	 * 
	 * */

	function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));

				}


	class Users extends DBAbstract{

		private $attributes = array();
		/**
		 * 
		 * @brief Al instanciar hace autocarga de atributos
		 * 
		 * ejectuta el constructor de DBAbstract
		 * Realiza auto creación de atributos en la clase en base a la tabla
		 * 
		 * */
		function __construct(){
			parent::__construct();


			/**< Obtiene información de la tabla */
		 	$request = $this->query("DESCRIBE users");

		 	$request = $request->fetch_all(MYSQLI_ASSOC);

			foreach ($request as $key => $value) {

				$var = $value["Field"];

				/**< Guarda los nombres de las columnas */
				$this->atrributes[] = $var;

				$this->$var = "";
			}
		}

		/**
		 * 
		 * Desloguea al usuario
		 * 
		 * */
		function logout(){

			session_unset();

			session_destroy();

			return ["errno" => 200, "error" => "Ha cerrado su sesión"];
		}


		/**
		 * 
		 * soft delete sobre el usuario
		 * 
		 * 
		 * */
		function leaveOut(){

			$fecha_hora = date("Y-m-d H:i:s");
			$id = $this->id;

			$ssql = "UPDATE users SET delete_at='$fecha_hora' WHERE id=$id";

			$this->query($ssql);

		}


		/**
		 * 
		 * Verifica el token enviado al email para validar al usuario
		 * @brief valida el token email
		 * @param array $form [token]
		 * @return array [error, errno]
		 * 
		 * */

		function verify($form){

			/*< recupera el token del array*/
			$token = $form["token"];

			var_dump($token);

			/*< consulta para buscar el usuario por medio de su token*/
			$ssql = "SELECT * FROM `users` WHERE email_token = '$token'";

			/*< ejecuta la consulta*/
			$response = $this->query($ssql)->fetch_all(MYSQLI_ASSOC);
			
			
			/*< si se encontro el usuario*/
			if(count($response)>0){

			
 				/*< activa el usuario y borra el token email*/
				$ssql = "UPDATE users SET active = '1' WHERE email_token = '$token'";

				var_dump($token);

				/*< ejecuta la consulta*/
				$this->query($ssql);

				/*< parámetros para hacer login, como la contraseña esta cifrada le avisamos al login que lo está*/

				// $form = ["txt_email" => $response[0]["email"], "txt_contraseña" => $response[0]["pass"], "cifrado" => 1];

				// /*< ejecuta el login */
			
				// $this->login($form);

				header('Location: /login');
				


				/*< mensaje de validación exitosa*/
				return ["errno" => 200, "error" => "Se valido el email"];
				

			}

			/*< el token no existe o no está relacionado a ningún usuario*/
			return ["errno" => 404, "error" => "El token es invalido"];

		}

		/**
		 * 
		 * Valida el usuario y contraseña
		 * @param array $form formulario de logueo sin el botón
		 * @return array errno and error
		 * 
		 * */
		function login($form){

			/*< valida que el métod http sea GET*/
			if($_SERVER["REQUEST_METHOD"]!="POST"){
				return ["errno" => 405, "error" => "Metodo invalido"];
			}



			/*< valida que el usuario ya haya iniciado sesión*/
			if(isset($_SESSION['huertaenred'])){
				return ["errno" => 406, "error" => "Ya esta logueado no puede volver a loguearse"];
			}

			/*< valida que este txt_email*/
			if(!isset($form["txt_email"])){
				return ["errno" => 407, "error" => "falta txt_email"];
			}

			/*< valida que este txt_contraseña*/
			if(!isset($form["txt_contraseña"])){
				return ["errno" => 407, "error" => "falta txt_contraseña"];
			}
			
			/*< recupera el email*/
			$email = $form["txt_email"];

			// si existe el elemento cifrado la contraseña proporcionada no se cifra
			if(isset($form['cifrado'])){
				$pass = $form["txt_contraseña"];
			}else{
				$pass = md5($form["txt_contraseña"]);
			}
			

			// averigua si el email existe en la tabla de users
			$result = $this->query("CALL `login`('$email')");


		
			$result = $result->fetch_all(MYSQLI_ASSOC);


			// si no hay filas
			if(count($result)==0){
				return ["errno" => 404, "error" => "Email no registrado"];
			}

			// si la contraseña no coincide
			if($result[0]["pass"]!=$pass){
				return ["errno" => 400, "error" => "Contraseña invalida"];
			}


			$sql = "SELECT `active` FROM `users` WHERE email = '$email'";
	

			$result = $this->query($sql);

			$result = $result->fetch_all(MYSQLI_ASSOC);

			if($result[0]["active"]== "0"){
				return ["errno" => 400, "error" => "No haz activado tu cuenta"];
			}




			// carga los atributos con los datos del usuario
			foreach ($this->atrributes as $key => $attribute) {
				$this->$attribute = $result[0][$attribute];	
			}
			
			/*< Pasa toda la información del usuario a la variable de sesión */
			$_SESSION['huertaenred']['user'] = $this;

			// retorna un mensaje de logueo satisfactorio
			return ["errno" => 200, "error" => "Usuario logueado"];
		}

		/**
		 * 
		 * Actualiza los datos del usuario
		 * @param array $form formulario sin el botón
		 * @return array errno and error
		 * 
		 * */
		function update($form){

			$nombre = $form["txt_nombre"];
			$id = $this->id;

			// actualiza el nombre
			$sql = "CALL `users_update`('$nombre','$id')";

			// ejecuta la consulta
			$this->query($sql);

			// reemplaza el atributo nombre con el valor nuevo
			$this->first_name = $nombre;

			// retorna el mensaje de que esta todo bien
			return ["errno" => 200, "error" => "Se actualizaron los datos"];
		}

		/**
		 * 
		 * Agrega un nuevo usuario si no existe en la tabla de usuarios el correo
		 * @param array $form formulario sin el botón
		 * @return array errno and error
		 * 
		 * */
		function register($form){

			$email = $form["txt_email"];
			// encripta la contraseña
			$pass = md5($form["txt_contraseña"]);

			// Averigua si el email ya esta en la tabla de users
			$result = $this->query("SELECT * FROM users WHERE email = '$email'");

			$result = $result->fetch_all(MYSQLI_ASSOC);

			// si no hay resultado entonces podemos agregar el usuario
			if(count($result)==0){

				/*< genera el token para enviar en el email y validar el usuario*/
				$email_token = md5($_ENV['TOKEN_APP'].date("YmdHis").mt_rand(0,1000));

				// inserta el nuevo usuario
				$sql = "INSERT INTO users (email, pass, email_token) VALUES ('$email', '$pass', '$email_token')";

				$this->query($sql);

				// id del nuevo usuario
				// $this->id = $this->db->insert_id;

				/*< carga la plantilla de email de validación*/
				$tpl = new Pork("email/validation");

				/*< crea el objeto para enviar el email*/
				$mailer = new Mailer();

				/*< motivo del email*/
				$asunto = "Registro de usuario";

				/*< carga las variables de la plantilla de email*/
				$tpl->setVars(["EMAIL_TOKEN" => $email_token]);

				/*< la plantilla se pasa a una variable para que se imprima en el email*/
				$correo = $tpl->buffer;

				/*< envia el email de validación*/
				$response = $mailer->send($email, $asunto, $correo);

				// mensaje de exito al agregar
				return ["errno" => 200, "error" => "Usuario agregado"];
			}

			// Es alguien que volvio arrastrandose?
			if($result[0]["delete_at"]!='0000-00-00 00:00:00'){
				$fecha_hora = date("0000-00-00 00:00:00");
				$id = $result[0]["id"];
				$this->id = $id;

				$ssql = "UPDATE users SET delete_at='$fecha_hora', first_name='', last_name='' WHERE id=$id";

				$this->query($ssql);

				// mensaje del email ya esta registrado
				return ["errno" => 201, "error" => "Usuario vuelve arrastrandose"];
			}

			// mensaje del email ya esta registrado
			return ["errno" => 400, "error" => "Email ya registrado"];
		}

		/**
		 * 
		 * Busca un usuario por medio de su email
		 * @param string $email correo electrónico del usuario
		 * @return array datos con datos del usuario
		 * 
		 * */
		function getByEmail($email){

			// Busca el email
			$result = $this->query("SELECT * FROM users WHERE email = '$email'");

			$result = $result->fetch_all(MYSQLI_ASSOC);

			// carca el atributo nombre con el nombre del usuario
			$this->nombre = $result[0]["first_name"];

			return $result;
		}

		/**
		 * 
		 * Cantidad total de usuarios
		 * @return int cantidad de usuarios
		 * 
		 * */
		function cant(){
			$this->query("SELECT * FROM users");
			return $this->db->affected_rows;
		}

		/**
		 * 
		 * @brief Retorna un listado de usuarios limitado
		 * @param array $params [inicio], [cantidad]
		 * @return array listado de usuarios
		 * 
		 * */
		function getAll($params){

			/*< si no es por method GET error */
			if($_SERVER['REQUEST_METHOD']!='GET'){
				return ["errno" => 405, "error"=> "Metodo http incorrecto"];
			}

			if(!isset($_SESSION["huertaenred"])){
				return ["errno" => 408, "error"=> "El método solo esta disponible para usuarios"];
			}

			/*< inicio por defecto en 0*/
			$vars[0] = 0;

			/*< valida que exista el parametro de inicio*/
			if(isset($params["inicio"])){
				$vars[0] = $params["inicio"];	
			}

			/*< si no existe cantidad error*/
			if(!isset($params["cantidad"])){
				return ["errno" => 406, "error"=> "Falta cantidad"];
			}

			$vars[1] = $params["cantidad"];

			$ssql = "SELECT * FROM users LIMIT {$vars[0]},{$vars[1]}";

			return $this->query($ssql)->fetch_all(MYSQLI_ASSOC);

		}


		function listadoboletas($form){

    // Obtener el número de residencia del formulario
    $nro_residencia = $form['txt_nro_residencia'];

    // Consulta SQL para seleccionar todas las boletas asociadas al número de partida
    $ssql = "SELECT * FROM `boleta` WHERE Nro_partida = '$nro_residencia'";

    return $this->query($ssql)->fetch_all(MYSQLI_ASSOC);
}


function boleta($form){

    
    $nro_residencia = $form['nroresidencia'];

    // Consulta SQL para seleccionar todas las boletas asociadas al número de partida
    $ssql = "SELECT * FROM `boleta` WHERE Nro_partida = '$nro_residencia'";

    return $this->query($ssql)->fetch_all(MYSQLI_ASSOC);
}








    function reclamo($form){

    	$nombre=$form["nombre_apellido"];
    	$telefono=$form["telefono"];
    	$email=$form["email"];
    	$mensaje=$form["mensaje"];
    	$token= generateToken();
    

	    $ssql= "INSERT INTO `reclamos`(`nombre`, `telefono`, `email`, `mensaje`, `id_usuario`,`estado`,`token_reclamo`) VALUES ('$nombre','$telefono','$email','$mensaje',NULL,0,'$token')";

    	  $this->query($ssql);

    }


    function respondidos($form){

    	$token_reclamo=$form["token_reclamo"];


    	$ssql="UPDATE `reclamos` SET `estado` = 1 WHERE `token_reclamo` = '$token_reclamo' AND `estado` = 0";

    	  $this->query($ssql);

    		// var_dump($ssql);
    }



    function mostrarreclamo($form){

    	$ssql= "SELECT * FROM `reclamos`";

    	 return $this->query($ssql)->fetch_all(MYSQLI_ASSOC);
 	
    }



       function obtenerNoticiaPorToken($token) {
       	
        $ssql = "SELECT * FROM novedades WHERE token_noticia = '$token'";
        return $this->query($ssql)->fetch_all(MYSQLI_ASSOC);
    }


          function actualizarNoticia($token, $titulo, $fecha, $tipo, $img, $informacion) {
        $ssql = "UPDATE novedades 
                 SET titulo = '$titulo', fecha = '$fecha', tipo_novedad = '$tipo', img = '$img', informacion = '$informacion' 
                 WHERE token_noticia = '$token'";
        return $this->query($ssql);
    }
    


function landingnoticias($form){

	$ssql="SELECT * FROM `novedades` WHERE 1";

		 return $this->query($ssql)->fetch_all(MYSQLI_ASSOC);


}

   function listadonoticia($form){

	$ssql="SELECT * FROM `novedades` WHERE 1";

		 return $this->query($ssql)->fetch_all(MYSQLI_ASSOC);


}  





function novedades($form){

	$ssql="SELECT * FROM `novedades` WHERE 1";

		 return $this->query($ssql)->fetch_all(MYSQLI_ASSOC);


} 









   
       function listeventos($form){

    	$ssql="SELECT * FROM `eventos` WHERE 1";
    	
    	return $this->query($ssql)->fetch_all(MYSQLI_ASSOC);


    }

    function enviarmail($form){


    	 	$email=$form["email"];

    	 	$msj=$form["mensaje"];

    	 	$mailer = new Mailer();

    	 	$motivo="reclamo";

			 	$pork = '<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Registro - {{PROJECT_NAME}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
        }
        .header h1 {
            margin: 0;
            color: #8BC34A;
        }
        .content {
            padding: 20px;
        }
        .content p {
            line-height: 1.6;
        }
        .button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px 0;
            background-color: #8BC34A;
            color: #ffffff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #999999;
            padding: 20px 0;
        }
    </style>
</head>
<body>

	<!-- {{PROJECT_NAME}} -->
<!-- {{PROJECT_DESCRIPTION}} -->
<!-- {{PROJECT_AUTHOR}} -->
<!-- {{PROJECT_AUTHOR_CONTACT}} -->
<!-- {{PROJECT_WEB}} -->
<!-- {{PROJECT_KEYWORDS}} -->
<!-- {{PROJECT_MODE}} -->

    <div class="container">
        <div class="header">
            <h1>convivencia</h1>
        </div>
        <div class="content">
            <p>¡Holaaaaaaaaaaa	¡Ultimo Momento!</p>
             el mesj es: '.$msj.'
        
            
            
            <p>Si no te registraste en <strong>convivencia</strong>, puedes ignorar este correo electrónico.</p>
        </div>
        <div class="footer">
            <p>&copy; 2024 convivencia. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>';


			$correo = $pork;

				/*< envia el email de validación*/

			$response = $mailer->send($email, $motivo, $correo);

				/*< si no existe cantidad error*/
		
			return ["errno" => 406, "error"=> "enviado con exito al email"];
			


    }






		function publicacion($form){

			$token_email = $form["token"];

			$ssql= "SELECT * FROM `novedades` WHERE `token_noticia` = '$token_email'";

			return $this->query($ssql)->fetch_all(MYSQLI_ASSOC);

		}






		function suscriptores($form){

			$email_suscriptor= $form["txt_email"];

			$ssql="INSERT INTO `suscriptores`(`email`) VALUES ('$email_suscriptor')";

				$this->query($ssql);

		}





    function eventos($form){

    	$titulo_evento=$form["txt_titulo"];
    	$fecha= $form["txt_fecha"];
    	$descripcion=$form["txt_des"];
    	$time= $form["txt_time"];
    	$time_final=$form["txt_time_final"];
    	$ubicacion=$form["txt_ubicacion"];
    	$img_evento=$form["txt_img"];
    	$token_evento=generateToken();


    	$ssql="INSERT INTO `eventos`(`id_evento`, `titulo`, `fecha`, `horario`, `ubicacion`, `img_evento`, `token_evento`, `final_horario`,`descripcion`) VALUES (NULL,'$titulo_evento','$fecha','$time','$ubicacion','$img_evento','$token_evento','$time_final','$descripcion')";

    	$this->query($ssql);




    }

  



    function busqueda($form){

    	$fecha=$form["fecha"];
    	//esto me devuelve todos  horarios de una fecha seleccionada

    	$ssql="SELECT h.horario FROM turnos_dni h LEFT JOIN turnos_agendados ho ON h.horario = ho.hora_turno WHERE h.fecha = '$fecha' AND ho.hora_turno IS NULL ";   

    	return $this->query($ssql)->fetch_all(MYSQLI_ASSOC);

    }

    function turnosasignados($form){


    	$dni=$form["txt_dni"];
    	$fecha= $form["fecha"];
    	$hora= $form["horarios"];
    	$estado="activo";
    	
    	$ssql="INSERT INTO `turnos_agendados`(`id_turno`, `dni_usuario`, `fecha_turno`, `hora_turno`, `estado_turno`) VALUES (NULL,'$dni','$fecha','$hora','$estado')";


    	$this->query($ssql);
    }



    function listadofechas($form){
    	//esto lista las fechas disponibles para sacar turno del dni
    $ssql = "SELECT DISTINCT fecha FROM turnos_dni";
    return $this->query($ssql)->fetch_all(MYSQLI_ASSOC);
}
 




  

    function noticia($form){

    	$titulo= $form["titulo_txt"];
    	$fecha=$form["fecha_txt"];
    	$novedad=$form["optionSelect"];
    	$url=$form["img_url"];
    	$mensaje=$form["mensaje_txt"];
    	$token=generateToken();


    	$ssql="INSERT INTO `novedades`(`id_noticia`, `titulo`, `fecha`, `tipo_novedad`, `img`, `informacion`, `token_noticia`) VALUES (NULL,'$titulo','$fecha','$novedad','$url','$mensaje','$token')";

    	$this->query($ssql);

    	$sql="SELECT `email` FROM `suscriptores`";

    	 $this-> query($sql);

    	  $thengris = $this->query($sql)->fetch_all(MYSQLI_ASSOC);

    	  $mailer = new Mailer();

    	  $asunto = "NUEVA NOTICIA";


			 foreach ($thengris as $key => $value) {

			 	$pork = '<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Registro - {{PROJECT_NAME}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
        }
        .header h1 {
            margin: 0;
            color: #8BC34A;
        }
        .content {
            padding: 20px;
        }
        .content p {
            line-height: 1.6;
        }
        .button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px 0;
            background-color: #8BC34A;
            color: #ffffff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #999999;
            padding: 20px 0;
        }
    </style>
</head>
<body>

	<!-- {{PROJECT_NAME}} -->
<!-- {{PROJECT_DESCRIPTION}} -->
<!-- {{PROJECT_AUTHOR}} -->
<!-- {{PROJECT_AUTHOR_CONTACT}} -->
<!-- {{PROJECT_WEB}} -->
<!-- {{PROJECT_KEYWORDS}} -->
<!-- {{PROJECT_MODE}} -->

    <div class="container">
        <div class="header">
            <h1>convivencia</h1>
        </div>
        <div class="content">
            <p>¡Holaaaaaaaaaaa	¡Ultimo Momento!</p>
            <p>'.$titulo.'</p>
            <p>'.$novedad.'</p>
            <a href="https://urbanagest.tecnica3.com.ar/noticia?token='.$token.'" class="button">Ver pasdublicacion</a>
            
            <p>Si no te registraste en <strong>convivencia</strong>, puedes ignorar este correo electrónico.</p>
        </div>
        <div class="footer">
            <p>&copy; 2024 convivencia. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>';
				/*< la plantilla se pasa a una variable para que se imprima en el email*/
				$correo = $pork;

				/*< envia el email de validación*/
				$response = $mailer->send($value["email"], $asunto, $correo);


    }







    

}
	

}
 ?>