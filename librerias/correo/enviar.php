<?php
date_default_timezone_set('Etc/UTC');
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 0;
$mail->Debugoutput = 'html';
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = "info.ocean2018@gmail.com";
$mail->Password = "parque2018";
$hoy = Date('d/m/Y');
$mail->setFrom('info.ocean2018@gmail.com');
foreach ($enviar_a as $value) {
	$mail->addAddress($value);
}
//$mail->addAddress('dsantacruz360@gmail.com');
$mail->Subject = $titulo;
$mail->IsHTML(true);
$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
$mail->AltBody = 'This is a plain-text message body';
$mail->Body    = $mensaje;
if (!$mail->send()) {
    //echo "Error al enviar mensaje: " . $mail->ErrorInfo;
    echo "Error al enviar mensaje. vuelve a probar mas tarde.";
} else {
    //echo "Mensaje Enviado!";

}

?>