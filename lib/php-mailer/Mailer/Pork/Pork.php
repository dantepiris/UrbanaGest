<?php 

	/**
	 * 
	 * Motor de plantillas
	 * 
	 * */
	class Pork{

		public $buffer;
		private $vista;

		/**
		 * 
		 * Carga la vista especificada al momento de la instancia
		 * @param string $name nombre la vista
		 * 
		 * */
		function __construct($name){
			$this->load($name);
		}

		/**
		 * 
		 * Carga la vista y la retorna como un string
		 * @param string $tpl es el nombre de la vista
		 * 
		 * */
		function load($tpl){
			$this->vista = $tpl;

			if(!file_exists('views/'.$tpl.'View.html')){
				echo "Error la vista <b>$tpl</b> no existe.";
				exit();
			}

			$this->buffer = file_get_contents('views/'.$tpl.'View.html');

			/*< ni bien cargo la vista, se cargan los componentes externos*/
			$this->loadExterns();

			/*< ni bien tengo todos los componentes reemplazo las variables de entorno*/
			$vars_env = [];


			 $env_vars = ['PROJECT_NAME', 
			'PROJECT_AUTHOR', 
			'PROJECT_WEB', 
			'PROJECT_MODE', 
			'PROJECT_AUTHOR_CONTACT', 
			'PROJECT_KEYWORDS', 
			'PROJECT_DESCRIPTION'
			 ];

			 foreach ($env_vars as $key => $env_var) {
				
			if(isset($_ENV[$env_var])){
				$vars_env=array_merge($vars_env,[$env_var => $_ENV[$env_var]]);
			}	
			 }

			/*< escribe en la vista las variables de entorno*/
			$this->setVars($vars_env);

		}

		/**
		 * 
		 * Reemplaza las variables dentro de la plantilla
		 * @param array $var es un vector asociativo nombre variable y valor
		 * 
		 * */
		function setVars($var){
			foreach ($var as $needle => $value) {

				if($this->testVar($needle)){
					$this->buffer = str_replace("{{".$needle."}}", $value, $this->buffer);
				}else{

					echo "Error la variable <b>$needle</b> no existe en la vista <b>".$this->vista."</b>";

					exit();
				}
			}
		}

		/**
		 * 
		 * Verifica que una variable exista dentro de la vista
		 * @return bool existe|no existe la variable 
		 * 
		 * */
		private function testVar($name){
			return strpos($this->buffer, "{{".$name."}}");
		}

		/**
		 * 
		 * @brief carga los componentes externos ej: @extern('name')
		 * 
		 * */
		private function loadExterns(){

			/*< almacenará los @extern('name') */
			$query_externs= [];

			/*< patrón de busqueda @extern('x') */
			$pattern =  "/@extern\(['\"]([a-zA-Z0-9_]+)['\"]\)/";

			/*< Busca todas las coincidencias con el pattern y las almacena en $query_externs */
			preg_match_all($pattern, $this->buffer, $query_externs);

			/*< Recorre todos nombres de los @extern */
			foreach ($query_externs[1] as $key => $file_name_extern) {

				/*< verifica si existe el archivo especificado en el @extern */
				if(!file_exists('views/'.$file_name_extern.".html")){

					/*< mensaje de error en caso de no encontrar el archivo */
					echo "Error extern <b>$file_name_extern</b> no existe |</b>";
					exit();
				}

				/*< carga en una variable temporal el contenido del archivo */
				$temp_buffer = file_get_contents('views/'.$file_name_extern.".html");

				/*< reemplaza en la vista el @extern('x') con el contenido del archivo especificado */
				$this->buffer = str_replace($query_externs[0][$key], $temp_buffer, $this->buffer);
			}
		}

		/**
		 * 
		 * Retorna el contenido del atributo $vista
		 * @return string nombre de la vista
		 * 
		 * */
		function getVista(){
			return $this->vista;
		}

		/**
		 * 
		 * Imprime la vista en pantalla
		 * 
		 * */
		function print(){
			echo $this->buffer;
		}

	}


 ?>