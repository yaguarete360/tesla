<?php if(!isset($_SESSION)) {session_start();}
    
    echo 'Intentando enviar...<br/>';

    require '../../librerias/PHPMailer_5.2.4/class.phpmailer.php';

    if(isset($mails_a))
    {
        foreach ($mails_a as $mail_a)
        {
            try
            {
                $mail = new PHPMailer(true); //New instance, with exceptions enabled

                $body             = $texto;

                $mail->IsSMTP();                           // tell the class to use SMTP
                $mail->SMTPAuth   = true;                  // enable SMTP authentication
                $mail->Port       = 587;                    // set the SMTP server port
                $mail->SMTPSecure = 'ssl';
                $mail->Host       = "gator4066.hostgator.com"; // SMTP server
                $mail->Username   = $mail_desde['cuenta'];     // SMTP server username
                $mail->Password   = $mail_desde['contrasena'];            // SMTP server password

                $mail->IsSendmail();  // tell the class to use Sendmail

                $mail->AddReplyTo($mail_desde['cuenta'], $mail_desde['cuenta']);

                $mail->From       = $mail_desde['cuenta'];
                $mail->FromName   = $mail_desde['cuenta'];

                $mail->AddAddress($mail_a);

                $mail->Subject    = $sujeto;

                $mail->AltBody    = $texto; // optional, comment out and test
                $mail->WordWrap   = 80; // set word wrap

                $mail->MsgHTML($body);

                $mail->IsHTML(true); // send as HTML

                $mail->Send();
                echo '<br/>';
                echo 'Mensajes enviado de '.$mail_desde['cuenta'].' a '.$mail_a.'<br/>';
            }
            catch (phpmailerException $e)
            {
                echo $e->errorMessage();
            }
        }
    }

?>
