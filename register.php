<?php
require_once("consultas.php");
$consultas = new Consultas();
$data=array();

if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password'] || empty($_POST['telefono']))) {
    $data['success'] = false;
    $data['message'] = 'Campos Vacios al enviar los datos';
}else{
    $usuario = $consultas->registrar($_POST['name'], $_POST['email'], $_POST['password'], $_POST['telefono']);
    if ($usuario){
        $data['id'] = $usuario['id'];
        $data['name'] = ucwords($usuario['name']);
        $data['email'] = $usuario['email'];
        $data['telefono'] = $usuario['two_factor_secret'];
        $data['datos_cliente'] = $usuario['datos_cliente'];
        $data['success'] = true;
        $data['message'] = "Registrado correctamente";
    }else{
        $data['success'] = false;
        $data['message'] = "El correo electronico ya ha sido registrado anteriormente.";
    }

}
echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>