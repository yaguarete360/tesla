<?php if (!isset($_SESSION)) {session_start();}

if(isset($_SESSION['usuario_en_sesion']) and isset($_SESSION['alias_en_sesion']) and !empty($_SESSION['usuario_en_sesion']))
{
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
	
	include $url.'funciones/conectar-base-de-datos.php';
	
	$consulta_seleccion = 'SELECT * FROM visitas
    WHERE borrado LIKE "no"
    	AND visita LIKE "'.date("Y").'%"
    ORDER BY visita DESC
    LIMIT 1';

	$query_seleccion = $conexion->prepare($consulta_seleccion);
	$query_seleccion->execute();

	$numero_a_usar = 0;
	while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC))
	{
		$numero_a_usar = date('Y').'-'.str_pad(explode('-', $rows_seleccion['visita'])[1]+1, 7, "0", STR_PAD_LEFT);
	}

	$consulta = 'INSERT INTO visitas(
		id, 
		visita, 
		fecha,
		tipo, 
		direccion_ip,
		url_ingresado,
		alias, 
		usuario, 
		creado,
		borrado) 
	VALUES (
		0,
		"'.$numero_a_usar.'",
		"'.date('Y-m-d').'",
		"ingreso_seccion",
		"'.$direccion_ip.'",
		"'.$_SERVER['REQUEST_URI'].'",
		"'.$_SESSION['alias_en_sesion'].'",
		"'.$_SESSION['usuario_en_sesion'].'",
		"'.date('Y-m-d G:i:s').'",
		"no");';
	$q=$conexion->prepare($consulta);
	$q->execute(array());
}

?>
