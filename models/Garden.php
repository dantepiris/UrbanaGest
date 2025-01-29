<?php 
	/**
	* @file Garden.php
	* @brief Implementación de la clase Garden para el manejo de huertas.
	* @author Matias Leonardo Baez
	* @date 2024
	* @contact elmattprofe@gmail.com
	*/
	
	// Incluimos la clase que conecta a la base de datos
	include_once 'DBAbstract.php';

	/**
	 * 
	 * Clase para trabajar con la tabla de huertas
	 * 
	 * */
	class Garden extends DBAbstract{

		public $gardens = [];

		/**
		 * 
		 * @brief constructor de la clase
		 * Limpia la lista de huertas
		 * 
		 * */
		function __construct(){
			$this->garden = [];
		}


		/**
		 * 
		 * @brief Agrega una huerta a la lista
		 * @param string $name nombre de la huerta
		 * @return string nombre de la huerta nueva
		 * 
		 * */
		function add($name){
			$this->garden[] = $name;

			return $name;
		}

		/**
		 * 
		 * @brief Lista de productos
		 * @return array lista de productos
		 * 
		 * */
		function list(){
			return $this->garden;
		}
	}

 ?>