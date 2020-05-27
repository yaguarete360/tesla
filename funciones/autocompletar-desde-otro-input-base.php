<?php if (!isset($_SESSION)) {session_start();}

    $valor = (isset($_POST[$campo_nombre])) ? $_POST[$campo_nombre] : "";
	// echo '<span id="'.$span_a_actualizar.'"></span>';
    echo '<input type="text" id="'.$variable_nombre.'" name="'.$variable_nombre.'" value="'.$valor.'">';
    if(!isset($id_a_detectar_loading)) $id_a_detectar_loading = '';
?>

<script type="text/javascript">
    
    $('#'+'<?php echo $id_a_detectar_loading; ?>').keyup(function() {
        var span_a_actualizar = $('#'+'<?php echo $span_a_actualizar; ?>');
        if(!(span_a_actualizar.find('img').length > 0))
        {
            var barra_loading = ($(this).prop('value')) ? '<img src="../../imagenes/iconos/loading.gif">' : '';
            span_a_actualizar.html(barra_loading);
        }
    });

    $('#'+'<?php echo $id_a_detectar; ?>').on("click", "span", function (event) {
        var valor_a_buscar = $(this).html();
        valor_a_buscar = valor_a_buscar.replace("<span>","");
        valor_a_buscar = valor_a_buscar.replace("</span>","");
        var capitulo = '<?php echo $capitulo; ?>';
        if(capitulo == 'procesos' || capitulo == 'reportes')
        {
            var nivel_de_url = "../../";
        }
        else
        {
            var nivel_de_url = "../";
        }

        var datos_para_autocompletar = 'datos_para_el_query=<?php echo $campo_atributo["herramientas"]; ?>'+'='+valor_a_buscar;

        $.ajax({
            type: "POST",
            url: nivel_de_url+"funciones/autocompletar-desde-otro-input-consulta.php",
            data: datos_para_autocompletar,
            success: function(data) {
                $('#'+'<?php echo $id_a_actualizar; ?>').prop('value', data);
                // $('#'+'<?php echo $span_a_actualizar; ?>').html(data);
            }
        });


    });

</script>
