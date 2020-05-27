<?php if (!isset($_SESSION)) {session_start();}

include '../funciones/conectar-base-de-datos.php';

$sitio_a_buscar = $_POST['sitio_a_buscar'];

$sitio = array();
$consulta_seleccion = 'SELECT estado, cuenta FROM sitios WHERE borrado = "no" AND sitio = "'.$sitio_a_buscar.'" ORDER BY sitio ASC';
$query_seleccion = $conexion->prepare($consulta_seleccion);
$query_seleccion->execute();
while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC))
{
    $sitio['cuenta'] = $rows_seleccion['cuenta'];
    $sitio['estado'] = $rows_seleccion['estado'];
}

echo json_encode($sitio);

?>
