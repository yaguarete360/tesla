<?php if(!isset($_SESSION)) {session_start();}
    
    if(!isset($requerido)) $requerido = '';
    if(!isset($contador_de_armador_contratos)) $contador_de_armador_contratos = 0;
    if(!isset($valor)) $valor = 0;
    $contador_de_armador_contratos++;

    // echo '<input type="text" name="'.$campo_nombre.'" value="'.$valor.'" maxlength="15" class="input_factura" '.$requerido.'>';
    echo '<input type="text" name="'.$campo_nombre.'" value="'.$valor.'" maxlength="16" data-formatear-contrato="si" '.$requerido.'>';

?>

<script type="text/javascript">

    if('<?php echo $contador_de_armador_contratos; ?>' == 1) // Crear el evento una sola vez porque es por clase
    {
        function pad (str, max) {
         str = str.toString();
         return str.length < max ? pad("0" + str, max) : str;
        }

        // Agregar a un input type="text" data-formatear-factura-numero="si"
        $("[data-formatear-contrato]").keyup(function(){
                texto = $(this).val().replace(/-/g, '');
                linea = (texto.substr(0,1) ? texto.substr(0,1) : '');
                centro = '';
                numero = '';
                numero_1 = '';
                if(texto.length > 1)
                {
                    centro = '-'+(texto.substr(1,3) ? texto.substr(1,3) : '');
                }
               
                if(texto.length > 4)
                {
                    var texto_parseado = parseInt(texto.substr(4), 10);
                    if(texto_parseado)
                    {
                        texto_parseado+= '';
                        numero_1 = (texto_parseado.substr(0, 7) ?  texto_parseado.substr(0, 7): '');
                    }
                    if(numero_1) numero = '-'+pad(numero_1, 7);
                }

                console.log(linea);
                console.log(centro);
                console.log(numero);
                contrato = linea+centro+numero;
                $(this).prop('value', contrato);
            });
    }

</script>
