<?php if (!isset($_SESSION)) {session_start();}

include "../../funciones/conectar-base-de-datos.php";

$dia_desde = $dia - 3;
$dia_hasta = $dia + 4;

if(isset($_GET['difunto']))
{

    $consulta = 'SELECT 
      id, 
      fecha, 
      tipo, 
      difunto,
      defuncion_fecha,
      nacimiento,
      cementerio_origen,
      sitio                    
      FROM operaciones_difuntos
      WHERE borrado = "0000-00-00"
      AND tipo LIKE "Entierro"
      AND difunto NOT LIKE "*%"
      AND difunto NOT LIKE "S/D%"                 
      AND MONTH(fecha) LIKE "'.$mes.'"
      AND DAY(fecha) BETWEEN "'.$dia_desde.'"
      AND "'.$dia_hasta.'"
      AND difunto LIKE "%'.$_GET['difunto'].'%"
      ORDER BY fecha
      DESC
      LIMIT 500'
    ;

}
else
{

    $consulta = 'SELECT 
      id, 
      fecha, 
      tipo, 
      difunto,
      defuncion_fecha,
      nacimiento,
      cementerio_origen,
      sitio
      FROM operaciones_difuntos
      WHERE borrado = "0000-00-00"
      AND tipo LIKE "Entierro"
      AND difunto NOT LIKE "*%"
      AND difunto NOT LIKE "S/D%" 
      AND MONTH(defuncion_fecha) LIKE "'.$mes.'"
      AND DAY(defuncion_fecha) BETWEEN "'.$dia_desde.'"
      AND "'.$dia_hasta.'"                
      ORDER BY fecha
      DESC
      LIMIT 500'
    ;

}

$query = $conexion->prepare($consulta);
$query->execute();

$misas = array();
while($rows = $query->fetch(PDO::FETCH_ASSOC))
{
    $misas[$rows['id']]['id'] = $rows['id'];
    $misas[$rows['id']]['defuncion_fecha'] = $rows['defuncion_fecha'];
    $misas[$rows['id']]['difunto'] = $rows['difunto'];
    $misas[$rows['id']]['nacimiento'] = $rows['nacimiento'];
    $misas[$rows['id']]['cementerio_origen'] = $rows['cementerio_origen'];
    $misas[$rows['id']]['sitio'] = $rows['sitio'];
}

?>
