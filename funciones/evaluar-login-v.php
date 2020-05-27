<?php if (!isset($_SESSION)) {session_start();}

$url = "../";

date_default_timezone_set('America/Asuncion');

if(empty($_POST['usr']) or empty($_POST['pswd']))
{
    
	$_SESSION['logeado'] = "no";		
	session_destroy();
	header('Location:../index.php');
}
else
{
	$_SESSION['usr'] =  $_POST['usr'];
	$_SESSION['pswd'] =  $_POST['pswd'];
	$client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
    
    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $direccion_ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $direccion_ip = $forward;
    }
    else
    {
        $direccion_ip = $remote;
    }
    
    $pswCorrecta = "no";
	
	include $url.'funciones/conectar-base-de-datos.php';
	
	$consulta_seleccion = 'SELECT *
    FROM visitas
    WHERE borrado LIKE "no"
    AND visita LIKE "'.date("Y").'%"
    ORDER BY visita
    DESC
    LIMIT 1';

	$query_seleccion = $conexion->prepare($consulta_seleccion);
	$query_seleccion->execute();

	while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC))
	{
		$ultimo_numero = $rows_seleccion['visita'];
	}
	
	if(!isset($ultimo_numero))
	{		
		$numero = 0;
	} 		
	else
	{		
		$numero_partes = explode("-", $ultimo_numero);
		$numero = $numero_partes[1];
		$numero++;
	}
	
	$numero_a_usar = date("Y")."-".str_pad($numero, 7, "0", STR_PAD_LEFT);
	
	$consulta = 'SELECT *
	FROM organigrama
	WHERE alias = "'.$_SESSION['usr'].'"
	AND clave = "'.$_SESSION['pswd'].'"
	AND borrado LIKE "no"';

	$query = $conexion->prepare($consulta);
	$query->execute();
	
	try 
	{
	  while($rows = $query->fetch(PDO::FETCH_ASSOC))
	{
		
		$_SESSION['usuario_en_sesion'] = $rows['organigrama'];
		$_SESSION['alias_en_sesion'] = $rows['alias'];
		$_SESSION['nivel'] = $rows['nivel'];
		$_SESSION['cedula_en_sesion'] = $rows['documento_numero'];
		$_SESSION['logeado'] = "si";
		$consulta = 'INSERT INTO visitas(
		id, 
		visita, 
	    fecha,
		tipo, 
		direccion_ip,
		alias, 
		usuario, 
		creado,
		borrado) 
		VALUES (
		0,
		"'.$numero_a_usar.'", 
		"'.date('Y-m-d').'", 
		"login",			 
		"'.$direccion_ip.'",
		"'.$_SESSION['alias_en_sesion'].'", 
		"'.$_SESSION['usuario_en_sesion'].'", 
		"'.date('Y-m-d G:i:s').'",
		"no");';
		//el campo momento era cargado por CURRENT_TIMESTAMP pero traia hora y fecha de servidor y no de local. Cambie a date() para tener hora paraguaya
		$q=$conexion->prepare($consulta);
		$q->execute(array());
		$_SESSION['usr'] = "";
		$_SESSION['pswd'] = "";
		$pswCorrecta = "si";
	}
	
} 
catch (Exception $e) 
{
    echo $e;
    die();
}
	header('Location:../index.php');
} 

?>
