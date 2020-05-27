<?php
    
    require_once(__DIR__.'/../inc/config.php');
    
    if(isset($_POST['name']) && isset($_POST['email']) && strtolower($_POST['captcha']) == strtolower($_SESSION["captcha"])){

        $de = "<p>";
        $de .= "<b>Difunto:</b> ".$_POST['difunto']."<br />";
        $de .= "<b>Nombre:</b> ".$_POST['name']."<br />";
        $de .= "<b>Email:</b> ".$_POST['email']."<br />";
        $de .= "<b>Familiar:</b> ".$_POST['family']."<br />";
        $de .= "<b>Mensaje:</b><br />".nl2br($_POST['message'])."<br />";
        $de .= "</p>";

        $mensaje = "<html>
                        <head>
                            <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                            <title>Parque Serenidad</title>
                        </head>
                        <body>
                            <p><h2>Enviado desde el formulario de condolencias del sitio web de Parque Serenidad</h2></p>
                            ".$de."
                        </body></html>\n\n";
        
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: Parque Serenidad<'.$site_config['email_contact'].'>' . "\r\n";
        
        if (mail( $site_config['email_condolencias_form'], 'Formulario de condolencias', $mensaje, $headers)) {
            echo json_encode(array('status' => 1));
        } else {
            echo json_encode(array('status' => 0));
        }

    }