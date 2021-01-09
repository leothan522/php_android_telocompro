<?php
require_once("consultas.php");
$consultas = new Consultas();
$name = null;
$email = null;
$error = null;
$password = null;
$data=array();

$data['name'] = false;
$data['email'] = false;


if (!empty($_POST['name'])){
    $update = $consultas->update('name', $_POST['name'], $_POST['id']);
    if ($update == "update"){
        $data['name'] = ucwords($_POST['name']);
        $name = true;
    }
}
if (!empty($_POST['email'])){
    $update = $consultas->update('email', $_POST['email'], $_POST['id']);
    if ($update == "update"){
        $data['email'] = strtolower($_POST['email']);
        $email = true;
    }else{
        if ($update == "error"){
            $error = "email";
        }
    }

}


if (!empty($_POST['password']) && !empty($_POST['nuevo_password'])){
    $update = $consultas->updatePassword($_POST['password'], $_POST['nuevo_password'], $_POST['id']);
    if ($update == "update"){
        $password = true;
    }else{
        if ($update == "incorrecto"){
            $error = "password";
        }
    }

}


if ($name || $email || $password){
    $data['error'] = false;
    $data['message'] = "Cambios Guardados Correctamente";
}else{

    switch ($error){

        case "email":
            $data['error'] = true;
            $data['message'] = "El correo electronico ya ha sido registrado anteriormente.";
        break;
        case "password":
            $data['error'] = true;
            $data['message'] = "Password Incorrecto.";
        break;

        default:
            $data['error'] = true;
            $data['message'] = "No se realizo ningun cambio.";
        break;
    }

}


echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>