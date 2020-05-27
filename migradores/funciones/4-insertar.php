<?php if (!isset($_SESSION)) {session_start();}

if(isset($inserciones))
{	
	foreach($inserciones as $insercion)
	{				
		$query_insercion = $conexion_pse->prepare($insercion);
		$query_insercion->execute();
	}
}

echo '<hr/>';
echo '<span style="color: green"><b>Todos los registros fueron revisados y/o insertados CORRECTAMENTE.</b></span><br/>';
echo '<br/>';
echo '<img src="../migradores/imagenes/exito.jpg" alt="Exito" style="width:150px;height:150px;">';
echo '<hr/>';

?>		
