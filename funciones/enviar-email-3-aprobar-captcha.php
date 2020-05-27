<?php if (!isset($_SESSION)) {session_start();}

if (isset($_POST['captcha'])) 
{
    if(strtolower($_POST['captcha']) != strtolower($_SESSION["captcha"]))
    {
        $captcha_autorizado = "no";
    }
    else
    {
        $captcha_autorizado = "si";
    }
}

?>
