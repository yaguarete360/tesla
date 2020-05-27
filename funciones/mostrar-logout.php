<?php if (!isset($_SESSION)) {session_start();}

$url = "../";

include $url.'funciones/evaluar-logout.php';

if(!isset($_SESSION['usr']) || !isset($_SESSION['pswd']))
{
	header('Location:../index.php');
}

?>
