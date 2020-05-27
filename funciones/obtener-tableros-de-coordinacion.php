 
<?php if (!isset($_SESSION)) {session_start();}

include "../../funciones/conectar-base-de-datos.php";

include "../../vistas/datos/difuntos.php";

$casos = 1;

$consulta = 'SELECT *
FROM difuntos
WHERE concluido = "no"
AND difunto NOT LIKE "*%"
AND difunto NOT LIKE "S/D%"
AND borrado = "no"
ORDER BY fecha
DESC,
codigo
DESC
LIMIT 500';

$query = $conexion->prepare($consulta);
$query->execute();

while($rows = $query->fetch(PDO::FETCH_ASSOC))
{
	foreach ($_SESSION['campos'] as $campo_nombre => $campo_atributo) 
    {

        $rotulos[$campo_nombre][$casos] = $campo_nombre;
        $valores[$campo_nombre][$casos] = $rows[$campo_nombre];
    }
    $casos++;
}

?>
