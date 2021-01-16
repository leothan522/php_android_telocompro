<?php
require_once("consultas.php");
$consultas = new Consultas();
$filas = null;
$data=array();

if (empty($_POST['email']) && empty($_POST['password'])) {
    $data['error'] = true;
    $data['message'] = 'Campos Vacios al enviar los datos';
}else{
    $usuario = $consultas->login($_POST['email'], $_POST['password']);
    if ($usuario){
        $data['id'] = $usuario['id'];
        $data['name'] = ucwords($usuario['name']);
        $data['email'] = strtolower($usuario['email']);
        $data['id_cliente'] = $usuario['id_cliente'];
        $data['error'] = false;
        $data['message'] = "Bienvenido ".ucwords($data['name']);
    }else{
        $data['error'] = true;
        $data['message'] = "Estas credenciales no coinciden con nuestros registros.";
    }

}

echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>