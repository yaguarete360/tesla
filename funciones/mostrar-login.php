<?php if (!isset($_SESSION)) {session_start();}				

// $url = "../../";
$url = (isset($capitulo) and ($capitulo == 'reportes' or $capitulo == 'procesos')) ? "../../" : "../";

$buscar = "";

echo '<form method="post" action="'.$url.'funciones/evaluar-login.php">';
	echo '<center>';
		echo '<input type="text" name="usr" placeholder="Usuario" size="34"/>';
		echo '<input type="password" name="pswd" placeholder="Clave"/>';
		echo '<input type="submit" name="login" value="Login"/>';
		echo '<input type="reset" name="reset" value="Reset"/>';
		echo '<input type="hidden" name="ubicacion_del_header" value="'.ltrim($_SERVER['REQUEST_URI'], '/parqueserenidad.com/').'"/>';
	echo '</center>';		
echo '</form>';

?>
