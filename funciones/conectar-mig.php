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
            $_SESSION['dbUsuario'] = '';
            $_SESSION['dbClave'] = '';
        break;
        case 'hostgator':
            $_SESSION['dbServidor'] = 'localhost';
            $_SESSION['dbNombre'] = '';
            $_SESSION['dbUsuario'] = '';
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
