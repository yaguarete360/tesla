<?php if (!isset($_SESSION)) {session_start();}
    
    $herramientas_explotado = explode("-", $campo_atributo['herramientas']);
    $tabla_a_usar = $herramientas_explotado[0];
    $campo_a_usar = $herramientas_explotado[1];
    $herramientas_sub_explotado = array();
    if(isset($herramientas_explotado[2])) $herramientas_sub_explotado = explode("#", $herramientas_explotado[2]);
    $campos_filtro = array();
    foreach ($herramientas_sub_explotado as $pos => $herramientas_sub) $campos_filtro[] = $herramientas_sub;

    if(!isset($requerido)) $requerido = '';
    $valor = (isset($_POST[$campo_nombre])) ? $_POST[$campo_nombre] : (isset($valor) ? $valor : '');
    $nombre_de_input = (isset($con_nombre_de_variable) and $con_nombre_de_variable == 'no') ? '' : 'name="'.$campo_nombre.'"';
	echo '<input type="text" id="'.$campo_nombre.'_busqueda" '.$nombre_de_input.' value="'.$valor.'" autocomplete="off" '.$requerido.' style="width:100%"/>';
	echo '<div id="'.$campo_nombre.'_sugerencias" class="autocompletar_sugerencias"></div>';

    $campos_filtro_implotado = implode("#", $campos_filtro)."#".$tabla_a_usar;

    if(!isset($con_href[0])) $con_href[0] = false;
    if($con_href[0])
    {
        echo '<td>';
            $direccion = $con_href[1];
            $gets_adicionales = array_slice($con_href, 2);
            foreach ($gets_adicionales as $get_adicional)
            {
                $switch_get_adicional = explode('-', $get_adicional);
                switch ($switch_get_adicional[0])
                {
                    case 'con_rango_de_fecha':
                        $gets_adicionales_a['fd'] = date('Y-m-d', strtotime(date('Y-m-d').' - '.$switch_get_adicional[1]));
                        $gets_adicionales_a['fh'] = date('Y-m-d');
                    break;
                }
            }
            // echo '<a href="../reportes/clientes-estado_de_cuenta.php?cu='.str_replace(',', '_', $cuenta_a_buscar).'&fd='.$fecha_desde_estado.'&fh='.$fecha_hasta_estado.'" target="_blank">';
            $data_href_inicial = '../reportes/clientes-estado_de_cuenta.php?';
            foreach ($gets_adicionales_a as $get_llave => $get_valor) $data_href_inicial.= $get_llave.'='.$get_valor.'&';
            echo '<a id="'.$campo_nombre.'_href" href="" data-href_inicial="'.$data_href_inicial.'" target="_blank">';
                echo '<img src="../../imagenes/iconos/boton-ver-documentos.png" alt="Ver Estado Estado de Cuenta" width="20" height="20">';
            echo '</a>';
        echo '</td>';
    }

?>

<script type="text/javascript">
    
    $('#'+'<?php echo $campo_nombre; ?>_sugerencias').css('display', 'none');

    $('#'+'<?php echo $campo_nombre; ?>_busqueda').keyup(function(){
        // if($('input[name="grabar"]').length > 0) $('input[name="grabar"]').attr('disabled', true);
        if($(this).val().length > 2)
        {
            var capitulo = '<?php echo $capitulo; ?>';
            if(capitulo == 'procesos' || capitulo == 'reportes')
            {
                var nivel_de_url = "../../";
            }
            else
            {
                var nivel_de_url = "../";
            }

            var datos_para_autocompletar = 'datos_para_el_query=<?php echo $campos_filtro_implotado."#".$campo_a_usar."="; ?>'+$(this).val();
            console.log('hola');
            $.ajax({
                type: "POST",
                url: nivel_de_url+"funciones/autocompletar-consulta.php",
                data: datos_para_autocompletar,
                success: function(data) {
                    $('#'+'<?php echo $campo_nombre; ?>_sugerencias').html(data).css('display', 'block');
                },
                error: function(data, xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    $('#'+'<?php echo $campo_nombre; ?>_sugerencias').html('Error: '+datos_para_autocompletar+"\n"+err).css('display', 'block');
                },
            });
        }
        else
        {
            $('#'+'<?php echo $campo_nombre; ?>_sugerencias').css('display', 'none');
        }
    });
    
    $('#'+'<?php echo $campo_nombre; ?>_sugerencias').on("click", "span", function (event) {
        var comienzo_del_substr = ($(this).html().includes(' - ')) ? $(this).html().indexOf(' - ') + 3 : 0;
        var este_valor = $(this).html().substring(comienzo_del_substr);
        $('#'+'<?php echo $campo_nombre; ?>_busqueda').prop('value', este_valor);
        $('#'+'<?php echo $campo_nombre; ?>_sugerencias').css('display', 'none');
        // $('#'+'<?php echo $campo_nombre; ?>_busqueda').focus();
        if($('#'+'<?php echo $campo_nombre; ?>_href').length > 0)
        {
            $('#'+'<?php echo $campo_nombre; ?>_href').attr('href', $('#'+'<?php echo $campo_nombre; ?>_href').data('href_inicial')+'cu='+este_valor.replace(/,/g , "_"));
        }
        // console.log($('input .grabar'));
        // if($('input[name="grabar"]').length > 0) $('input[name="grabar"]').attr('disabled', false);
    });

    $('body').on('click',function(event){
       if(!$(event.target).is('#'+'<?php echo $campo_nombre; ?>_sugerencias')){
         $('#'+'<?php echo $campo_nombre; ?>_sugerencias').css('display', 'none');
       }
    });

</script>
