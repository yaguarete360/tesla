<?php if(!isset($_SESSION)) {session_start();}

        (isset($rows[$campo_nombre])) ? $valor = $rows[$campo_nombre] : $valor = "";
        
        if(!empty($valor)) $valor_explotado = explode("-", $valor);

            echo "<b>Emprendimiento</b>";
            echo '<br/>';
            (isset($valor_explotado[0])) ? $sitio_emprendimiento = $valor_explotado[0] : $sitio_emprendimiento = "";
            echo '<input type="text" name="sitio_emprendimiento" id="sitio_emprendimiento" class="datos" value="'.$sitio_emprendimiento.'" maxlength="1"/>';
        echo '</td>';
    echo '</tr>';

    echo '<tr>';
        echo '<td '.$estilo_del_td.'>';
        echo '</td>';
        echo '<td class="td_columna2" '.$estilo_del_td.'>';
            echo "<b>Linea</b>";
            echo '<br/>';
            (isset($valor_explotado[1])) ? $sitio_linea = $valor_explotado[1] : $sitio_linea = "";
            echo '<input type="text" name="sitio_linea" id="sitio_linea" class="datos" value="'.$sitio_linea.'" maxlength="1"/>';
        echo '</td>';
    echo '</tr>';

    echo '<tr>';
        echo '<td '.$estilo_del_td.'>';
        echo '</td>';
        echo '<td class="td_columna2" '.$estilo_del_td.'>';
            echo "<b>Area</b>";
            echo '<br/>';
            (isset($valor_explotado[2])) ? $sitio_area = $valor_explotado[2] : $sitio_area = "";
            echo '<input type="text" name="sitio_area" id="sitio_area" class="datos" value="'.$sitio_area.'" maxlength="2"/>';
        echo '</td>';
    echo '</tr>';

    echo '<tr>';
        echo '<td '.$estilo_del_td.'>';
        echo '</td>';
        echo '<td class="td_columna2" '.$estilo_del_td.'>';
            echo "<b>Sendero</b>";
            echo '<br/>';
            (isset($valor_explotado[3])) ? $sitio_sendero = $valor_explotado[3] : $sitio_sendero = "";
            echo '<input type="text" name="sitio_sendero" id="sitio_sendero" class="datos" value="'.$sitio_sendero.'" maxlength="3"/>';
        echo '</td>';
    echo '</tr>';

    echo '<tr>';
        echo '<td '.$estilo_del_td.'>';
        echo '</td>';
        echo '<td class="td_columna2" '.$estilo_del_td.'>';
            echo "<b>Sitio</b>";
            echo '<br/>';
            (isset($valor_explotado[4])) ? $sitio_sitio = $valor_explotado[4] : $sitio_sitio = "";
            echo '<input type="text" name="sitio_sitio" id="sitio_sitio" class="datos" value="'.$sitio_sitio.'" maxlength="4"/>';
        echo '</td>';
    echo '</tr>';

    echo '<tr>';
        echo '<td '.$estilo_del_td.'>';
        echo '</td>';
        echo '<td class="td_columna2" '.$estilo_del_td.'>';
            echo "<b>Codigo Del Sitio</b>";
            echo '<br/>';
            echo '<input type="text" name="'.$campo_nombre.'" class="datos" id="armar_sitio" value="'.$valor.'" readonly/>';
        echo '</td>';
    echo '</tr>';

    echo '<tr>';
        echo '<td '.$estilo_del_td.'>';
        echo '</td>';
        echo '<td id="td_'.$campo_nombre.'" class="td_columna2" '.$estilo_del_td.'>';
            echo "<b>Titular 1:&nbsp&nbsp&nbsp</b>";
            echo '<span id="dato_a_mostrar_sitio" style="font-weight:bold;"></span>';
        echo '</td>';
    

    $consulta_seleccion = 'SELECT sitio, cuenta
        FROM sitios
        WHERE borrado = "no"
            AND estado = "vendido"
        ORDER BY sitio ASC';

        $query_seleccion = $conexion->prepare($consulta_seleccion);
        $query_seleccion->execute();

        while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC))
        {
            $sitios_posibles[$rows_seleccion['sitio']] = $rows_seleccion['cuenta'];
        }
?>

<script type="text/javascript">

    // var sitios_posibles = '<?php echo json_encode($sitios_posibles); ?>';
    // var sitios_posibles = JSON.parse(sitios_posibles);

    // console.log(sitios_posibles);

    $('#sitio_emprendimiento,#sitio_linea,#sitio_area,#sitio_sendero,#sitio_sitio').change(function()
    {
        var sitio_emprendimiento = $('#sitio_emprendimiento').val();
        var sitio_linea = $('#sitio_linea').val();
        var sitio_area = $('#sitio_area').val();
        var sitio_sendero = $('#sitio_sendero').val();
        var sitio_sitio = $('#sitio_sitio').val();


        if (/^[0-9]+$/.test(sitio_emprendimiento)
            &&/^[0-9]+$/.test(sitio_linea)
            && /^[0-9]+$/.test(sitio_area)
            && /^[0-9]+$/.test(sitio_sendero)
            && /^[0-9]+$/.test(sitio_sitio))
        {
            var pad_area = "00";
            var sitio_area_f = pad_area.substring(0, pad_area.length - sitio_area.length) + sitio_area;

            var pad_sendero = "000";
            var sitio_sendero_f = pad_sendero.substring(0, pad_sendero.length - sitio_sendero.length) + sitio_sendero;

            var pad_sitio = "0000";
            var sitio_sitio_f = pad_sitio.substring(0, pad_sitio.length - sitio_sitio.length) + sitio_sitio;
            
            var sitio_final = sitio_emprendimiento+"-"+sitio_linea+"-"+sitio_area_f+"-"+sitio_sendero_f+"-"+sitio_sitio_f;
            
            // if(sitios_posibles[sitio_final])
            // {
            //     document.getElementById("dato_a_mostrar_sitio").innerHTML = sitios_posibles[sitio_final];
            //     $('#armar_sitio').val(sitio_final);
            // }
            // else
            // {
            //     alert('El sitio '+sitio_final+' no es valido.');
            // }
            $('#armar_sitio').val(sitio_final);
            alert('Revisar el codigo del '+sitio_final+' para asegurarse de que sea correcto.');

        }

    });

</script>
