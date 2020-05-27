<?php if(!isset($_SESSION)) {session_start();}
    
    $tipo_del_input = ($campo_atributo['formato'] == "oculto") ? "hidden" : "text";
    echo '<input type="'.$tipo_del_input.'" id="'.$campo_nombre.'" name="'.$campo_nombre.'" class="datos" value="'.$rows[$campo_nombre].'" readonly/>';

    $filtros_del_asistente = "";
    $herramientas = explode("-", $campo_atributo['herramientas']);
    foreach ($herramientas as $pos => $herramienta)
    {
        switch ($pos)
        {
            case 0:
                $select_a_capturar = $herramienta;
            break;

            case 1:
                $partes_del_filtro_del_asistente = explode("=", $herramienta);
                $tabla_del_select = $partes_del_filtro_del_asistente[0];
                $campo_para_valor = $partes_del_filtro_del_asistente[1];
                $campo_para_key = $partes_del_filtro_del_asistente[2];
            break;
            
            default:
                $partes_del_filtro_del_asistente = explode("=", $herramienta);
                $filtros_del_asistente[$partes_del_filtro_del_asistente[1]] = $partes_del_filtro_del_asistente[0];
            break;
        }
    }
    //  --------// EJEMPLOS \\ -------
        // $filtros_del_asistente['productos'] = $herramientas[3]; WHERE $herramientas[3] LIKE "productos"
        // $filtros_del_asistente['areas de parques'] = 'agrupador'; WHERE agrupador LIKE "areas de parques"
        // $filtros_del_asistente['!0'] = 'es_cliente'; WHERE es_client e NOT LIKE "0"
    //  --------\\ EJEMPLOS //--------

    $consulta_seleccion = 'SELECT '.$campo_para_key.', '.$campo_para_valor.'
    FROM '.$tabla_del_select.'
    WHERE borrado LIKE "no"';
    if(!empty($filtros_del_asistente))
    {
        foreach ($filtros_del_asistente as $valor => $campo)
        {
            if(!empty($campo))
            {
                if($valor[0] == "!")
                {
                    $consulta_seleccion.= ' AND '.$campo.' NOT LIKE "'.substr($valor, 1).'"';
                }
                else
                {
                    $consulta_seleccion.= ' AND '.$campo.' LIKE "'.$valor.'"';
                }
            }
        }
    }
    $consulta_seleccion.= ' ORDER BY '.$campo_para_key.' ASC';
    
    $query_seleccion = $conexion->prepare($consulta_seleccion);
    $query_seleccion->execute();

    while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC))
    {
        $elementos_a_traer[$rows_seleccion[$campo_para_key]] = $rows_seleccion[$campo_para_valor];
    }

?>

<script type="text/javascript">

    <?php
        echo "var elementos_a_traer = ". json_encode($elementos_a_traer) .";\n";
    ?>
    
    $('<?php echo "#".$select_a_capturar; ?>').change(function(){
        if(elementos_a_traer)
        {
            var valor_a_usar = elementos_a_traer[this.value];
            if(valor_a_usar)
            {
                $('<?php echo "#".$campo_nombre; ?>').val(valor_a_usar);
            }
            else
            {
                $('<?php echo "#".$campo_nombre; ?>').val("sin datos");
            }
        }
        else
        {
            $('<?php echo "#".$campo_nombre; ?>').val("sin datos");
        }

    });

</script>
