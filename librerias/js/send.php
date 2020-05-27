<?php
    
    require_once(__DIR__.'/../inc/config.php');
    
    if(isset($_POST['name']) && isset($_POST['email']) && strtolower($_POST['captcha']) == strtolower($_SESSION["captcha"])){

        $motivos = array('', 'Consulta', 'Ventas', 'Reclamo', 'Sugerencia', 'Varios');
        $servicios = array('', 'Servicio de Sepelios', 'Crematorio', 'Cementerios Parque', 'Prepagas de Sepelio y Cremación', 'Féretros', 'Vehículos');
        $days = array('', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábados');

        $de = "<p>";
        $de .= "<b>Motivo:</b> ".$motivos[$_POST['motivo']]."<br />";
        $de .= "<b>Servicio:</b> ".$servicios[$_POST['servicio']]."<br />";
        $de .= "<b>Disponibilidad:</b> De ".$_POST['hora1']." a ".$_POST['hora2']."hs, de ".$days[$_POST['day1']]." a ".$days[$_POST['day2']]."<br />";
        $de .= "<b>Visita de un asesor:</b> ".ucfirst($_POST['asesor'])."<br />";
        $de .= "<b>Nombre:</b> ".$_POST['name']."<br />";
        $de .= "<b>Email:</b> ".$_POST['email']."<br />";
        $de .= "<b>Teléfono:</b> ".$_POST['tel']."<br />";
        $de .= "<b>Celular:</b> ".$_POST['cel']."<br />";
        $de .= "<b>Dirección:</b> ".$_POST['address']."<br />";
        $de .= "<b>Mensaje:</b><br />".nl2br($_POST['message'])."<br />";
        $de .= "</p>";

        $mensaje = "<html>
                        <head>
                            <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                            <title>Parque Serenidad</title>
                        </head>
                        <body>
                            <p><h2>Enviado desde el formulario de contacto del sitio web de Parque Serenidad</h2></p>
                            ".$de."
                        </body></html>\n\n";
        
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: Parque Serenidad<'.$site_config['email_contact'].'>' . "\r\n";
        
        if (mail( $site_config['email_contact_form'], 'Formulario de contacto', $mensaje, $headers)) {
            echo json_encode(array('status' => 1));
        } else {
            echo json_encode(array('status' => 0));
        }

    }