<?php if (!isset($_SESSION)) {session_start();}

$ultima_seccion = "";

include "../../funciones/conectar-base-de-datos.php";

$consulta = ' SELECT id, organigrama, grupo, seccion, sector, posicion
FROM organigrama
WHERE borrado = "no"
AND grupo = "funcionarios"
ORDER BY nivel,seccion, posicion,organigrama
ASC
LIMIT 250';

$query = $conexion->prepare($consulta);
$query->execute();

while($rows = $query->fetch(PDO::FETCH_ASSOC))
{
    $casos[$rows['id']]['id'] = $rows['id'];
    $casos[$rows['id']]['funcionario'] = $rows['organigrama'];
    $casos[$rows['id']]['seccion'] = $rows['seccion'];
    $casos[$rows['id']]['sector'] = $rows['sector'];
    $casos[$rows['id']]['posicion'] = $rows['posicion'];
}

echo '<center>';
    echo '<table>';
        foreach($casos as $vuelta => $caso)
        {
            if(!empty($casos[$vuelta]['seccion']))
            {                                           
                echo '<tr>';
                    echo '<td class="derecha">';                        
                        if($casos[$vuelta]['seccion'] != $ultima_seccion)
                        {                                                       
                            echo '<b>'.ucwords($casos[$vuelta]['seccion']).'</b>';
                        }                                                               
                    echo '</td>';
                    echo '<td>';
                        echo '&nbsp&nbsp&nbsp';
                    echo '</td>';
                    echo '<td>';                        
                        echo ucwords($casos[$vuelta]['funcionario']);                    
                    echo '</td>';
                echo '</tr>';
            }
            
            $ultima_seccion = $casos[$vuelta]['seccion'];
        
        }                                                          
    echo '</table>';
    echo '<br/>';
    echo '<br/>';
echo '</center>';

?>
