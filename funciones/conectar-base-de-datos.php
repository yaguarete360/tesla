<?php if (!isset($_SESSION)) {session_start();}

$servidor ='webserver';
$servidor ='mamp-marc';
$servidor ='mamp-enrique';

$servidor ='hostgator';

if(isset($servidor))
{
    switch ($servidor) 
    {
        
        case 'mamp-marc':
            $_SESSION['dbServidor'] = 'localhost';
            $_SESSION['dbNombre'] = 'pse_activa';
            $_SESSION['dbUsuario'] = '';
            $_SESSION['dbClave'] = '';
        break;
        case 'mamp-enrique':
            $_SESSION['dbServidor'] = 'localhost';
            $_SESSION['dbNombre'] = '';
            $_SESSION['dbUsuario'] = '';
            $_SESSION['dbClave'] = '';
        break;
        case 'webserver':
            $_SESSION['dbServidor'] = 'localhost';
            $_SESSION['dbNombre'] = '';
            // $_SESSION['dbUsuario'] = '';
            // $_SESSION['dbClave'] = ''; // password viejo a cambiar
            $_SESSION['dbUsuario'] = '';
            $_SESSION['dbClave'] = '!#';
        break;
        case 'hostgator':
            $_SESSION['dbServidor'] = 'localhost';
            $_SESSION['dbNombre'] = 'parquese_pse';
            // $_SESSION['dbUsuario'] = '';
            // $_SESSION['dbClave'] = '!!#!'; // password viejo a cambiar
            $_SESSION['dbUsuario'] = 'parque';
            $_SESSION['dbClave'] = '!#';
        break;
    }
   
    $conexion = new PDO("mysql:host={$_SESSION['dbServidor']};dbname={$_SESSION['dbNombre']};charset=utf8;COLLATE=utf8_general_ci", $_SESSION['dbUsuario'], $_SESSION['dbClave']);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
else
{
    echo '<div class="mensaje-rojo">No esta declarado ningun servidor</div>';
}

?>
