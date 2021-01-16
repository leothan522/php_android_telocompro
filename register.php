<?php
require_once("consultas.php");
$consultas = new Consultas();
$filas = null;
$data=array();

if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password'])) {
    $data['error'] = true;
    $data['message'] = 'Campos Vacios al enviar los datos';
}else{
    $usuario = $consultas->registrar($_POST['name'], $_POST['email'], $_POST['password']);
    if ($usuario){
        $data['id'] = $usuario['id'];
        $data['name'] = ucwords($usuario['name']);
        $data['email'] = $usuario['email'];
        $data['id_cliente'] = $usuario['id_cliente'];
        $data['error'] = false;
        $data['message'] = "Registrado correctamente";
    }else{
        $data['error'] = true;
        $data['message'] = "El correo electronico ya ha sido registrado anteriormente.";
    }

}
echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>