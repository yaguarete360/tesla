<?php if (!isset($_SESSION)) {session_start();}

$fecha_actual = date('Y-m-d');

$fecha_anterior = date ('Y-m-d', strtotime ('-'.$rango_dias.' day', strtotime($fecha_actual)));

if(!isset($_SESSION['fecha_desde'])) $_SESSION['fecha_desde'] = $fecha_anterior;
if(!isset($_SESSION['fecha_hasta'])) $_SESSION['fecha_hasta'] = $fecha_actual;
if(!isset($_SESSION['ano'])) $_SESSION['ano'] = date("Y");
if(!isset($_SESSION['mes'])) $_SESSION['mes'] = date("m");
if(!isset($_SESSION['letra'])) $_SESSION['letra'] = "";
if(isset($_POST['letra'])) 
{
	$letra = $_POST['letra'];
	$_SESSION['letra'] = $_POST['letra'];
}
else
{
	$letra = (!isset($_SESSION['letra'])) ? $letra : $_SESSION['letra'];
}

if(isset($_POST['millar'])) 
{
	$letra = $_POST['millar'];
	$_SESSION['millar'] = $_POST['millar'];
}
else
{
	$letra = (!isset($_SESSION['millar'])) ? $letra : $_SESSION['millar'];
}

if(isset($_POST['ano'])) 
{
	$ano = $_POST['ano'];
	$_SESSION['ano'] = $_POST['ano'];
}
else
{
	$ano = (!isset($_SESSION['ano'])) ? $ano : $_SESSION['ano'];
}

if(isset($_POST['mes'])) 
{
	$mes = $_POST['mes'];
	$_SESSION['mes'] = $_POST['mes'];
}
else
{
	$mes = (!isset($_SESSION['mes'])) ? $mes : $_SESSION['mes'];
}

if(isset($_POST['fecha_desde'])) 
{
	$fecha_desde = $_POST['fecha_desde'];
	$_SESSION['fecha_desde'] = $_POST['fecha_desde'];
}
else
{
	$fecha_desde = (!isset($_SESSION['fecha_desde'])) ? $fecha_anterior : $_SESSION['fecha_desde'];
}
if(isset($_POST['fecha_hasta'])) 
{
	$fecha_hasta = $_POST['fecha_hasta'];
	$_SESSION['fecha_hasta'] = $_POST['fecha_hasta'];
}
else
{
	$fecha_hasta = (!isset($_SESSION['fecha_hasta'])) ? $fecha_actual : $_SESSION['fecha_hasta'];
}

if(!isset($_SESSION['filtro_desde'])) $_SESSION['filtro_desde'] = "A*";
if(!isset($_SESSION['filtro_hasta'])) $_SESSION['filtro_hasta'] = "Z*";

if(isset($_POST['filtro_desde'])) 
{
	$filtro_desde = $_POST['filtro_desde'];
	$_SESSION['filtro_desde'] = $_POST['filtro_desde'];
}
else
{
	$filtro_desde = (!isset($_SESSION['filtro_desde'])) ? "A*" : $_SESSION['filtro_desde'];
}

if(isset($_POST['filtro_hasta'])) 
{
	$filtro_hasta = $_POST['filtro_hasta'];
	$_SESSION['filtro_hasta'] = $_POST['filtro_hasta'];
}
else
{
	$filtro_hasta = (!isset($_SESSION['filtro_hasta'])) ? "Z*" : $_SESSION['filtro_hasta'];
}
$sentido = (isset($_POST['asc'])) ? "ASC" : "DESC";
$sentido = (isset($_POST['des'])) ? "DESC" : "ASC";
?>