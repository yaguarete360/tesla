<?php if (!isset($_SESSION)) {session_start();}

    $calcular_iva = (isset($sumas_a_realizar['iva_monto'])) ? "iva_monto": "";
    $calcular_total = (isset($sumas_a_realizar['monto'])) ? "monto": "";

?>

<script type="text/javascript">
    
    var suma_iva = '<?php echo $calcular_iva; ?>';
    var suma_total = '<?php echo $calcular_total; ?>';

    $('#td_contratos').on('click','.'+'<?php echo $clase_a_detectar; ?>',function(){
        valor_total = 0;
        valor_iva = 0;

        setTimeout(function(){
            $('#tabla_principal .'+'<?php echo $clase_a_detectar; ?>').each(function(index, value){
                
                if($(this).prop('checked'))
                {
                    este_valor = +$(this).prop('value');
                    var nombre_de_la_mora = $(this).prop('name').replace('a_cobrar', 'a_cobrar_moras');
                    var monto_de_la_mora = 0;
                    if($('input[name="'+nombre_de_la_mora+'"]').length > 0)
                    {
                        var monto_de_la_mora = +$('input[name="'+nombre_de_la_mora+'"]').prop('value');
                        este_valor+= +monto_de_la_mora;
                    }

                    if(suma_total == "monto") valor_total+= +este_valor;
                    if(suma_iva == "iva_monto")
                    {
                        este_iva = $('.'+$(this).prop('class').split(' ')[1]+'-iva').prop('value');
                        este_iva = (100 / parseInt(este_iva)) + 1;
                        valor_iva = valor_iva + (este_valor / este_iva);
                    }
                }
            });
            
            valor_iva = Math.round(valor_iva);
            $('#'+'<?php echo $calcular_iva; ?>').prop('value', valor_iva);
            $('#'+'<?php echo $calcular_total; ?>').prop('value', valor_total);

            $('#'+'<?php echo $calcular_iva; ?>'+'-span').text(valor_iva.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
            $('#'+'<?php echo $calcular_total; ?>'+'-span').text(valor_total.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
        }, 5);

    });

</script>
