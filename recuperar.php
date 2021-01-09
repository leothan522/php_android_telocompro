<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'Constantes.php';

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
    $mail->setFrom(EMAIL_SENDER, 'Mailer');
    $mail->addAddress('leothan522@gmail.com');

    // Mensaje
    $mail->isHTML(true);
    $mail->Subject = 'Esta es una prueba de email';
    $mail->Body = 'Hola mundo desde <b>phpmailer</b>';
    $mail->AltBody = 'Este es un mensaje para los clientes que no soportan HTML.';

    $mail->send();
	//quiero forzar in commit

    echo 'Se envio el mensaje';
} catch (Exception $e) {
    echo "Algo salio mal al intentar enviar: {$mail->ErrorInfo}";
}