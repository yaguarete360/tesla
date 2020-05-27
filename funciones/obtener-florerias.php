<?php if (!isset($_SESSION)) {session_start();}

$consulta = 'SELECT *
	FROM agrupadores
	WHERE borrado = "no"
	AND agrupador = "florerias";
	ORDER BY descripcion;'
;

$query = $conexion->prepare($consulta);
$query->execute();

$ind = 0;

while($rows = $query->fetch(PDO::FETCH_ASSOC))
{
    $florerias[$ind]['id']        = $rows['id'];
    $florerias[$ind]['floreria']  = $rows['descripcion'];
    $florerias[$ind]['direccion'] = $rows['dato_1'];
    $florerias[$ind]['telefono']  = $rows['dato_2'];
    $florerias[$ind]['web']       = $rows['dato_3'];
    $florerias[$ind]['contacto']  = $rows['dato_4'];
    $ind++;
}

?>
