<?php if (!isset($_SESSION)) {session_start();}

$servidor ='hostgator';
$servidor ='mamp';

if(isset($servidor))
{
    switch ($servidor) 
    {
        case 'mamp':
            $_SESSION['dbServidor'] = 'localhost';
            $_SESSION['dbNombre'] = 'mig';
            $_SESSION['dbUsuario'] = 'root';
            $_SESSION['dbClave'] = 'a';
        break;
        case 'hostgator':
            $_SESSION['dbServidor'] = 'localhost';
            $_SESSION['dbNombre'] = 'parquese_mig';
            $_SESSION['dbUsuario'] = 'parquese_usuario';
            $_SESSION['dbClave'] = 'usuario2015';
        break;
    }
    $conexion_mig = new PDO("mysql:host={$_SESSION['dbServidor']};dbname={$_SESSION['dbNombre']};charset=utf8;COLLATE=utf8_general_ci", $_SESSION['dbUsuario'], $_SESSION['dbClave']);
    $conexion_mig->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
else
{
    echo '<div class="mensaje-rojo">No esta declarado ningun servidor</div>';
}

?>
