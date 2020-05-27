<?php if (!isset($_SESSION)) {session_start();}

$ultima_seccion = "";

include $url.'pse-red/funciones/conectar-base-de-datos.php';

$consulta = 'SELECT id, organigrama, grupo, seccion, sector, posicion
FROM organigrama
WHERE borrado = "no"
AND grupo = "funcionarios"
ORDER BY seccion, posicion
ASC
LIMIT 250';

$query = $conexion->prepare($consulta);
$query->execute();

while($rows = $query->fetch(PDO::FETCH_ASSOC))
{
    $casos[$rows['id']]['id'] = $rows['id'];
    $casos[$rows['id']]['funcionario'] = $rows['funcionario'];
    $casos[$rows['id']]['seccion'] = $rows['seccion'];
    $casos[$rows['id']]['organigrama'] = $rows['organigrama'];
    $casos[$rows['id']]['posicion'] = $rows['posicion'];
}

echo '<center>';
    echo '<table>';
        foreach($casos as $cas=>$caso)
        {
            if(!empty($casos[$cas]['seccion']))
            {                                           
                echo '<tr>';
                    echo '<td class="derecha">';
                        if($casos[$cas]['seccion'] != $ultima_seccion)
                        {                                                       
                            echo '<b>'.ucwords($casos[$cas]['seccion']).'</b>';
                        }                                           
                    echo '</td>';
                    echo '<td>';
                        echo '&nbsp&nbsp&nbsp';
                    echo '</td>';
                    echo '<td>';
                        echo ucwords($casos[$cas]['funcionario']);
                    echo '</td>';
                echo '</tr>';
            }
            $ultima_seccion = $casos[$cas]['seccion'];
        };                                                          
    echo '</table>';
    echo '<br/>';
    echo '<br/>';
echo '</center>';

?>
