<?php
 header("Access-Control-Allow-Origin: *");

class Conexion{
	public function get_conexion(){
		$user = "root";
		$pass = "";
		$host = "localhost";
		$db = "laravel-app";
		$conexion = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
		return($conexion);
	}
}

?>