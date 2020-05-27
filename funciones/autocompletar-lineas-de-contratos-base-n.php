<?php if (!isset($_SESSION)) {session_start();}

    $valor = (isset($_POST[$campo_nombre])) ? $_POST[$campo_nombre] : "";
	// echo '<span id="'.$variable_nombre.'_span"></span>';
    // echo 'Calcular Mora Al: <input type="date" name="fecha_de_calculo_de_mora" value=""><br/>';
    echo '<input type="hidden" name="'.$variable_nombre.'" value="'.$valor.'">';
    if(!isset($id_a_detectar_loading)) $id_a_detectar_loading = '';

?>

<script type="text/javascript">

    $('#'+'<?php echo $id_a_detectar_loading; ?>').keyup(function() {
        var span_a_actualizar = $('#'+'<?php echo $span_a_actualizar; ?>');
        if(!(span_a_actualizar.find('.barra_loading').length > 0))
        {
            var barra_loading = ($(this).prop('value')) ? '<img class="barra_loading" src="../../imagenes/iconos/loading.gif">' : '';
            span_a_actualizar.html(barra_loading);
        }
    });
    // $('#'+'<?php echo $id_a_detectar; ?>').on("click", "span", function (event) {
    $('#'+'<?php echo $id_a_detectar; ?>').on("click", function (event) {
        // var valor_a_buscar = $(this).html();
        var valor_a_buscar = $(this).val();
        console.log('valor_a_buscar='+valor_a_buscar);
        valor_a_buscar = valor_a_buscar.replace("<span>","");
        valor_a_buscar = valor_a_buscar.replace("</span>","");
        var comienzo_del_substr = (valor_a_buscar.includes(' - ')) ? valor_a_buscar.indexOf(' - ') + 3 : 0;
        var este_valor = valor_a_buscar.substring(comienzo_del_substr);
        var fecha_de_calculo_de_mora = $('input[name="fecha_de_calculo_de_mora"]').val();
        $.ajax({
            type: "POST",
            url: "../../funciones/autocompletar-lineas-de-contratos-consulta.php",
            // data: datos_para_autocompletar,
            data: {"datos_para_el_query": este_valor, "fecha_de_calculo_de_mora": fecha_de_calculo_de_mora},

            success: function(data) {
                $('#'+'<?php echo $span_a_actualizar; ?>').html(data);
            }
        });
    });

    $('#tabla_principal').on('change','.botones_a_cobrar',function(){
        
        var este_contrato_1 = $(this).prop('name').replace(' ', '_');
        contrato_desde = este_contrato_1.indexOf('[') + 1;
        contrato_hasta = este_contrato_1.indexOf(']');
        caracteres = contrato_hasta - contrato_desde;
        var este_contrato = este_contrato_1.substr(contrato_desde, caracteres);
        var anterior_chequeado = true;

        cantidad_de_botones_a_cobrar_chequeados = $('.botones_a_cobrar:checked').length;
        
        cantidad_maxima_de_items_a_cobrar = 1000;
        if(cantidad_de_botones_a_cobrar_chequeados > cantidad_maxima_de_items_a_cobrar)
        {
            alert('Ha alcanzado la cantidad maxima de items a cobrar. Cobre el resto en una nueva factura.');
            $(this).prop('checked', false);
        }
        else
        {

            if(!(este_contrato.includes('-var-')))
            {
                $('.'+este_contrato).each(function(index, value) {
                    if(anterior_chequeado)
                    {
                        $(this).prop('disabled', false);
                        // cuota_anterior_para_input = $(this).closest('tr').prev().find('.botones_a_cobrar').closest('td').prev();
                        // if($(this).is(':not(:checked)'))
                        // {
                        //     cuota_anterior_para_input_valor = cuota_anterior_para_input.html();
                        //     cuota_anterior_para_input.html('');
                        //     cuota_anterior_para_input.append('<input type="text" class="detectar_pago_parcial" style="width:100px;text-align:right;" value="'+cuota_anterior_para_input_valor+'">');
                        // }
                        // else
                        // {
                        //     cuota_anterior_para_input_input = cuota_anterior_para_input.find('.detectar_pago_parcial');
                        //     cuota_anterior_para_input_valor = cuota_anterior_para_input.find('.detectar_pago_parcial').val();
                        // }
                    }
                    else
                    {
                        $(this).prop('checked', false);
                        $(this).prop('disabled', true);
                    }
                    if($(this).prop('type') != 'hidden') anterior_chequeado = $(this).is(':checked');
                });
            }
        }
    });

</script>
