<?php if (!isset($_SESSION)) {session_start();}
    
    $fecha_hoy = date('Y-m-d G:i:s');
    $fecha_antes = date("Y-m-d G:i:s", strtotime(date('Y-m-d G:i:s') . ' -10 days'));

    $meses_s = ',Enero,Febrero,Marzo,Abril,Mayo,Junio,Julio,Agosto,Setiembre,Octubre,Noviembre,Diciembre';
    $meses_a = explode(",", $meses_s);

    $campos_a_mostrar_s = 'crematorio,capilla,cementerio_destino';
    $campos_a_mostrar_a = explode(",", $campos_a_mostrar_s);

    $consulta_obtencion_de_tablero = 'SELECT difunto,tipo,fecha,nombre_exequial,inicio_fecha,inicio_hora,fin_fecha,fin_hora,'.$campos_a_mostrar_s.' FROM difuntos
        WHERE borrado LIKE "no"
        AND fecha BETWEEN "'.$fecha_antes.'" AND "'.$fecha_hoy.'"
        AND concluido LIKE "no"
        AND difunto NOT LIKE "*%"
        AND difunto NOT LIKE "S/D%"
        AND oculto NOT LIKE "si"
        AND (tipo LIKE "sepelio"
            OR tipo LIKE "inhumacion"
            OR tipo LIKE "cremacion")';
    $query_obtencion_de_tablero = $conexion->prepare($consulta_obtencion_de_tablero);
    $query_obtencion_de_tablero->execute();

    $casos = 0;
    while($rows_odt = $query_obtencion_de_tablero->fetch(PDO::FETCH_ASSOC))
    {
        $nombre_a_usar = ($rows_odt['nombre_exequial'] != "sin datos" and $rows_odt['nombre_exequial'] != "no aplicable") ? $rows_odt['nombre_exequial'] : $rows_odt['difunto'];

        $datos_inicio_fecha = explode("-", $rows_odt['inicio_fecha']);
        $servicios_encontrados[$nombre_a_usar][$rows_odt['tipo']]['inicio'] = $datos_inicio_fecha[2]." de ".$meses_a[(int)$datos_inicio_fecha[1]]." ".substr($rows_odt['inicio_hora'], 0,5)." horas";
        
        $datos_fin_fecha = explode("-", $rows_odt['fin_fecha']);
        $servicios_encontrados[$nombre_a_usar][$rows_odt['tipo']]['fin'] = $datos_fin_fecha[2]." de ".$meses_a[(int)$datos_fin_fecha[1]]." ".substr($rows_odt['fin_hora'], 0,5)." horas";
        
        foreach ($campos_a_mostrar_a as $pos => $campo_nombre) $servicios_encontrados[$nombre_a_usar][$rows_odt['tipo']][$campo_nombre] = $rows_odt[$campo_nombre];

        $casos++;
    }
    
    $difuntos_a_mostrar = array_keys($servicios_encontrados);
?>
