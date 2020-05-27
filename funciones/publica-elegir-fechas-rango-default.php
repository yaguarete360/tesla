<?php if (!isset($_SESSION)) {session_start();}

$today = date('Y-m-d');
$newdate = date('Y-m-d', strtotime('-1 months', strtotime($today))); 
$dia_desde_default = $newdate;
$si_no_esta_definido_ejercicio = explode("-",date("Y-m-d"));
isset($_SESSION["ejercicio"])? $primero_de_enero = $_SESSION["ejercicio"].'-01-01' : $primero_de_enero = $si_no_esta_definido_ejercicio[0].'-01-01';
$dia_desde_default = $primero_de_enero;
$dia_hasta_default = date("Y-m-d");
isset($_GET["dia_desde"])? $_SESSION["dia_desde"] = $_GET["dia_desde"] : $_SESSION["dia_desde"] = $dia_desde_default;
isset($_GET["dia_hasta"])? $_SESSION["dia_hasta"] = $_GET["dia_hasta"] : $_SESSION["dia_hasta"] = $dia_hasta_default;

echo 'Desde el dia: <input type="date" name="dia_desde" value="'.$_SESSION["dia_desde"].'">'.$espacio_1;
echo 'hasta el dia: <input type="date" name="dia_hasta" value="'.$_SESSION["dia_hasta"].'">';

?>
