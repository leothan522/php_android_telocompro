<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'Constantes.php';
require_once("consultas.php");
$consultas = new Consultas();
$resultado = false;
$data=array();

if (empty($_POST['email'])) {
    $data['error'] = true;
    $data['message'] = 'Campos Vacios al enviar los datos';
    echo(json_encode($data));
}else{
	
	$validar_email = $consultas->validarEmail($_POST['email']);
	if($validar_email){
		$nueva_clave = $consultas->generate_string(8);
        $resultado = $consultas->recuperarPassword($validar_email, $nueva_clave);
        if ($resultado){
            $data['error'] = false;
            $data['message'] = "La nueva clave fue enviada por correo.";
        }else{
            $data['error'] = true;
            $data['message'] = "Algo salio mal al intentar enviar.";
        }
        echo(json_encode($data));

        // Al pasar true habilitamos las excepciones
        $mail = new PHPMailer(true);

        try {
            // Ajustes del Servidor
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER; // Comenta esto antes de producción
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = EMAIL_SENDER;
            $mail->Password = EMAIL_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Destinatario
            $mail->setFrom(EMAIL_SENDER, 'TE LO COMPRO');
            $mail->addAddress($_POST['email']);

            // Mensaje
            $mail->isHTML(true);
            $mail->Subject = 'Nuevo Password';
            $mail->Body = 'Hola, este es tu nuevo Password: <h4 style="color: blue">'.$nueva_clave.'</h4>';
            $mail->AltBody = 'Este es un mensaje para los clientes que no soportan HTML.';

            $mail->send();
            //echo 'Se envio el mensaje';
        } catch (Exception $e) {
            //echo "Algo salio mal al intentar enviar: {$mail->ErrorInfo}";
        }

    }else{
		$data['error'] = true;
		$data['message'] = "El Email no coincide con nuestros registros.";
        echo(json_encode($data));
	}
}

