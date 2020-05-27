<?php if (!isset($_SESSION)) {session_start();}

include "../../funciones/conectar-base-de-datos.php";

$consulta_secciones = 'SELECT descripcion, dato_1 FROM agrupadores WHERE borrado LIKE "no" AND agrupador LIKE "departamentos laborales" ORDER BY descripcion ASC';
$query_secciones = $conexion->prepare($consulta_secciones);
$query_secciones->execute();
while($rows_secciones = $query_secciones->fetch(PDO::FETCH_ASSOC)) $posiciones_de_seccion[$rows_secciones['descripcion']] = $rows_secciones['dato_1'];

$consulta_cargos = ' SELECT organigrama, cargo FROM organigrama WHERE borrado LIKE "no" ORDER BY cargo ASC';
$query_cargos = $conexion->prepare($consulta_cargos);
$query_cargos->execute();
while($rows_cargos = $query_cargos->fetch(PDO::FETCH_ASSOC)) $cargos_de_los_funcionarios[$rows_cargos['organigrama']] = $rows_cargos['cargo'];

$consulta_organigrama = ' SELECT id, organigrama, grupo, seccion, sector, posicion
FROM organigrama
WHERE borrado = "no"
    AND finalizacion = "0000-00-00"
    AND visible = "si"
ORDER BY organigrama ASC';

$query_organigrama = $conexion->prepare($consulta_organigrama);
$query_organigrama->execute();

while($rows_organigrama = $query_organigrama->fetch(PDO::FETCH_ASSOC))
{
    switch ($cargos_de_los_funcionarios[$rows_organigrama['organigrama']])
    {
        case 'director ejecutivo':
            $posicion_interna = '0';
        break;

        case 'asistente de direccion':
        case 'jefe de planta':
        case 'gerente de marketing':
            $posicion_interna = '1';
        break;

        case 'jefe de contabilidad':
        case 'jefa de contabilidad':
        case 'jefe de administracion':
        case 'jefe de seccion':
            $posicion_interna = '2';
        break;

        case 'jefe de carpinteria':
            $posicion_interna = '3';
        break;
        
        default:
            $posicion_interna = '4';
        break;
    }
    $posicion_de_la_seccion = (isset($posiciones_de_seccion[$rows_organigrama['seccion']])) ? $posiciones_de_seccion[$rows_organigrama['seccion']] : '03';
    $organigrama[$posicion_de_la_seccion.'-'.$rows_organigrama['seccion']][$posicion_interna.'-'.$rows_organigrama['organigrama']] = '';
    if($cargos_de_los_funcionarios[$rows_organigrama['organigrama']] == 'director ejecutivo') $organigrama['02-director ejecutivo'][$posicion_interna.'-'.$rows_organigrama['organigrama']] = '';
}

ksort($organigrama);

echo '<center>';
    echo '<table>';
        foreach($organigrama as $seccion => $funcionarios)
        {
            ksort($funcionarios);
            echo '<tr>';
                echo '<td class="derecha">';
                        echo '<b>'.ucwords(substr($seccion, 3)).'</b>';
                echo '</td>';
            $primer_funcionario = '';
            foreach ($funcionarios as $funcionario => $vacio)
            {
                if(!empty($primer_funcionario))
                {
                    echo '<tr>';
                        echo '<td>';
                        echo '</td>';
                }
                    echo '<td>';
                        echo ucwords(substr($funcionario, 2));
                    echo '</td>';
                echo '</tr>';
                $primer_funcionario = 'no';
            }
        }
    echo '</table>';
    echo '<br/>';
    echo '<br/>';
echo '</center>';

?>
