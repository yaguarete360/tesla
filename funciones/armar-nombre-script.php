<?php if (!isset($_SESSION)) {session_start();}

    if(!isset($valor) and isset($rows[$campo_nombre])) $valor = $rows[$campo_nombre];
    if(!isset($valor)) $valor = "";

    if(isset($listado_tipo))
    {
        if($listado_tipo == "altas" or $listado_tipo == "modificaciones") $esta_en_datos = "";
    }
    
    if(isset($esta_en_datos)) echo '</tr><tr>';
    
    if(!empty($valor)) $nombres_separados = explode(",", $valor);
    (isset($nombres_separados[0])) ? $difunto_nombres = trim($nombres_separados[0]) : $difunto_nombres = "";
    (isset($nombres_separados[1])) ? $difunto_apellidos = trim($nombres_separados[1]) : $difunto_apellidos = "";

        if(isset($esta_en_datos)) echo '<td '.$estilo_del_td.'></td>';
        if(isset($esta_en_datos)) echo '<td '.$estilo_del_td.'>';
            echo '<b><label>'.ucfirst($campo_nombre).' Nombres</label></b> &nbsp &nbsp';
        if(isset($esta_en_datos)) echo '</td><td '.$estilo_del_td.'>';
            $nombre_nombres = $campo_nombre."-nombres";
            echo '<input type="text" id="'.$nombre_nombres.'" class="datos" value="'.$difunto_nombres.'"/>';
        echo '</td>';
    echo '</tr>';
    echo '<tr>';
        //if(isset($esta_en_datos)) echo '<td '.$estilo_del_td.'></td>';
        echo '<td '.$estilo_del_td.'>';
        echo '</td>';
        echo '<td class="td_columna1" '.$estilo_del_td.'>';
            echo '<b><label>'.ucfirst($campo_nombre).' Apellidos</label></b> &nbsp &nbsp';
        if(isset($esta_en_datos)) echo '</td><td '.$estilo_del_td.'>';
            $nombre_apellidos = $campo_nombre."-apellidos";
            echo '<input type="text" id="'.$nombre_apellidos.'" class="datos" value="'.$difunto_apellidos.'"/>';
        
        echo '</td>';
    echo '</tr>';
    echo '<tr>';
        //if(isset($esta_en_datos)) echo '<td '.$estilo_del_td.'></td>';
        echo '<td '.$estilo_del_td.'>';
        echo '</td>';

        echo '<td class="td_columna1" '.$estilo_del_td.'>';
            echo '<b><label>'.ucfirst($campo_nombre).'</label></b> &nbsp &nbsp';
        if(isset($esta_en_datos)) echo '</td><td '.$estilo_del_td.'>';
            $nombre_destino = $campo_nombre."-destino";
            echo '<input type="text" id="'.$nombre_destino.'" name="'.$campo_nombre.'" class="datos" value="'.$valor.'" readonly/>';
        echo '</td>';

?>

<script type="text/javascript">

    apellidos = document.getElementById('<?php echo $nombre_nombres; ?>').value;
    nombres = document.getElementById('<?php echo $nombre_apellidos; ?>').value;
    
    $('<?php echo "#".$nombre_nombres; ?>, <?php echo "#".$nombre_apellidos; ?>').keyup(function()
    {
        nombres = $('<?php echo "#".$nombre_nombres; ?>').val().toLowerCase().replace(/^[\u00C0-\u1FFF\u2C00-\uD7FF\w]|\s[\u00C0-\u1FFF\u2C00-\uD7FF\w]/g, function(ucwords)
            {
                return ucwords.toUpperCase();
            });

        apellidos = $('<?php echo "#".$nombre_apellidos; ?>').val().toLowerCase().replace(/^[\u00C0-\u1FFF\u2C00-\uD7FF\w]|\s[\u00C0-\u1FFF\u2C00-\uD7FF\w]/g, function(ucwords)
            {
                return ucwords.toUpperCase();
            });

        concatenador = (apellidos && nombres) ? ', ' : '';

        console.log('nombres = '+nombres);
        console.log('apellidos = '+apellidos);
        console.log('concatenador = '+concatenador);

        nomFinal = apellidos+concatenador+nombres;
        $('<?php echo "#".$nombre_destino; ?>').val(nomFinal);
    });

</script>
