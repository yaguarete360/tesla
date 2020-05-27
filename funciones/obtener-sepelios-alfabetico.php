<?php if (!isset($_SESSION)) {session_start();}

$consulta_sel = 'SELECT 
    id, 
    difunto, 
    fecha, 
    nacimiento, 
    capilla,
    inicio_fecha,
    inicio_hora, 
    fin_fecha, 
    fin_hora, 
    cementerio_destino
    FROM difuntos
    WHERE borrado = "no"
    AND tipo LIKE "Sepelio"
    AND YEAR(fecha) BETWEEN "'.$desde_ano.'"
    AND "'.$hasta_ano.'"
    AND difunto NOT LIKE "*%"
    AND difunto NOT LIKE "S/D%" 
    AND difunto NOT LIKE "%n.n%"            
    AND difunto NOT LIKE "(%"           
    AND difunto NOT LIKE "%ngelito%"        	              
    ORDER BY fecha DESC'
;

$query = $conexion->prepare($consulta_sel);
$query->execute();

while($rows = $query->fetch(PDO::FETCH_ASSOC))
{
    $casos[$rows['id']]['id'] = $rows['id'];
    $casos[$rows['id']]['difunto'] = $rows['difunto'];
    $casos[$rows['id']]['fecha'] = $rows['fecha'];
    $casos[$rows['id']]['nacimiento'] = $rows['nacimiento'];
    $casos[$rows['id']]['capilla'] = $rows['capilla'];
    $casos[$rows['id']]['inicio_fecha'] = $rows['inicio_fecha'];
    $casos[$rows['id']]['inicio_hora'] = $rows['inicio_hora'];
    $casos[$rows['id']]['fin_fecha'] = $rows['fin_fecha'];
    $casos[$rows['id']]['fin_hora'] = $rows['fin_hora'];
    $casos[$rows['id']]['cementerio_destino'] = $rows['cementerio_destino'];
}

?>
