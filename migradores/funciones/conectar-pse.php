<?php if (!isset($_SESSION)) {session_start();}

$servidor ='hostgator';
$servidor ='mamp';

if(isset($servidor))
{
    switch ($servidor) 
    {
        case 'mamp':
            $_SESSION['dbServidor'] = 'localhost';
            $_SESSION['dbNombre'] = 'pse';
            $_SESSION['dbUsuario'] = 'root';
            $_SESSION['dbClave'] = 'a';
        break;
        case 'hostgator':
            $_SESSION['dbServidor'] = 'localhost';
            $_SESSION['dbNombre'] = 'parquese_pse';
            $_SESSION['dbUsuario'] = 'parquese_usuario';
            $_SESSION['dbClave'] = 'usuario2015';
        break;
    }
    $conexion_pse = new PDO("mysql:host={$_SESSION['dbServidor']};dbname={$_SESSION['dbNombre']};charset=utf8;COLLATE=utf8_general_ci", $_SESSION['dbUsuario'], $_SESSION['dbClave']);
    $conexion_pse->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
else
{
    echo '<div class="mensaje-rojo">No esta declarado ningun servidor</div>';
}

?>
