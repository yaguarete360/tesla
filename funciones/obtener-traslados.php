<?php if (!isset($_SESSION)) {session_start();}

if(isset($_GET['difunto']))
{
    
    $difunto_nombres = explode(" ",$_GET['difunto']);
    
    $a = isset($difunto_nombres[0]) ? $difunto_nombres[0] : "";
    $b = isset($difunto_nombres[1]) ? $difunto_nombres[1] : "";
    $c = isset($difunto_nombres[2]) ? $difunto_nombres[2] : "";
	
    $consulta = 'SELECT 
        id,
        difunto, 
        fecha, 
        inicio_fecha, 
        inicio_hora, 
        cementerio_origen, 
        cementerio_destino
        FROM difuntos
        WHERE borrado = "no"
        AND tipo LIKE "Traslado"
        AND difunto NOT LIKE "*%"
        AND difunto NOT LIKE "S/D%"
        AND difunto LIKE "%'.$a.'%"
        AND difunto LIKE "%'.$b.'%"
        AND difunto LIKE "%'.$c.'%"
        ORDER BY difunto
        ASC,
        fecha 
        DESC
        LIMIT 100'
	;

}
else
{
    
    $consulta = 'SELECT 
        id,
        difunto, 
        fecha, 
        inicio_fecha,
        inicio_hora,  
        cementerio_origen, 
        cementerio_destino
        FROM difuntos
        WHERE borrado = "no"
        AND tipo LIKE "Traslado"
        AND difunto NOT LIKE "*%"
        AND difunto NOT LIKE "S/D%"
        ORDER BY fecha
        DESC,
        difunto 
        ASC
        LIMIT 100'
	;

}

$query = $conexion->prepare($consulta);
$query->execute();

while($rows = $query->fetch(PDO::FETCH_ASSOC))
{
    $casos[$rows['id']]['id'] = $rows['id'];
    $casos[$rows['id']]['difunto'] = $rows['difunto'];
    $casos[$rows['id']]['fecha'] = $rows['fecha'];
    $casos[$rows['id']]['inicio_fecha'] = $rows['inicio_fecha'];
    $casos[$rows['id']]['inicio_hora'] = $rows['inicio_hora'];
    $casos[$rows['id']]['cementerio_origen'] = $rows['cementerio_origen'];
    $casos[$rows['id']]['cementerio_destino'] = $rows['cementerio_destino'];
}

?>
