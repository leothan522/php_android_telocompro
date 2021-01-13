<?php
require_once("consultas.php");
$consultas = new Consultas();
$filas = null;
$data=array();

// if (empty($_POST['email']) && empty($_POST['password'])) {
    // $data['error'] = true;
    // $data['message'] = 'Campos Vacios al enviar los datos';
// }else{
    // $usuario = $consultas->login($_POST['email'], $_POST['password']);
    // if ($usuario){
        // $data['id'] = $usuario['id'];
        // $data['name'] = ucwords($usuario['name']);
        // $data['email'] = strtolower($usuario['email']);
        // $data['error'] = false;
        // $data['message'] = "Bienvenido ".ucwords($data['name']);
    // }else{
        // $data['error'] = true;
        // $data['message'] = "Estas credenciales no coinciden con nuestros registros.";
    // }

// }

		$data['id'] = 1;
        $data['name'] = "Yonathan Castillo";
        $data['email'] = "Correo@gmail.com";
        $data['error'] = false;
        $data['message'] = "Bienvenido... ";
       //json_decode(file_get_contents('php://input'), true);

$recibido = null;

if($recibido = json_decode(file_get_contents('php://input'), true)){
	$email = $recibido['email'];
	//$email = "Correo loco";
	$data['message'] = $email;
}
echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>