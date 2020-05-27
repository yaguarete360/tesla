<?php if(!isset($_SESSION)) {session_start();}
    
    if(!isset($requerido)) $requerido = '';
    if(!isset($contador_de_facturas)) $contador_de_facturas = 0;
    if(!isset($valor)) $valor = 0;
    $contador_de_facturas++;

    // echo '<input type="text" name="'.$campo_nombre.'" value="'.$valor.'" maxlength="15" class="input_factura" '.$requerido.'>';
    echo '<input type="text" name="'.$campo_nombre.'" value="'.$valor.'" maxlength="16" data-formatear-factura-numero="si" '.$requerido.'>';

?>

<script type="text/javascript">

    if('<?php echo $contador_de_facturas; ?>' == 1) // Crear el evento una sola vez porque es por clase
    {
        // $(".input_factura").keyup(function(){
        //     texto = $(this).val().replace(/-/g, '');
        //     primeros_3 = (texto.substr(0,3) ? texto.substr(0,3)+'-' : '');
        //     segundos_3 = (texto.substr(3,3) ? texto.substr(3,3)+'-' : '');
        //     resto = (texto.substr(6) ? texto.substr(6) : '');
        //     factura = primeros_3+segundos_3+resto;
        //     $(this).prop('value', factura);
        // });

        function pad (str, max) {
         str = str.toString();
         return str.length < max ? pad("0" + str, max) : str;
        }

        // Agregar a un input type="text" data-formatear-factura-numero="si"
        $("[data-formatear-factura-numero]").keyup(function(){
                texto = $(this).val().replace(/-/g, '');
                primeros_3 = (texto.substr(0,3) ? texto.substr(0,3) : '');
                segundos_3 = '';
                resto = '';
                resto_1 = '';
                if(texto.length > 3)
                {
                segundos_3 = '-'+(texto.substr(3,3) ? texto.substr(3,3) : '');
                }
               
                if(texto.length > 6)
                {
                var texto_parseado = parseInt(texto.substr(6), 10);
                if(texto_parseado)
            {
            texto_parseado+= '';
                resto_1 = (texto_parseado.substr(0, 7) ?  texto_parseado.substr(0, 7): '');
            }
                if(resto_1) resto = '-'+pad(resto_1, 7);
                }

                factura = primeros_3+segundos_3+resto;
                $(this).prop('value', factura);
            });
    }

</script>
