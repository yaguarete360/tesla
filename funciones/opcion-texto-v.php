<?php if (!isset($_SESSION)) {session_start();}

if($_SESSION['alias_en_sesion'] == "admin")
{
    $agrupadores_permitidos['todos'] = "permitido";
    $agrupadores_permitidos['nuevos'] = "permitido";
}
else
{
    $consulta_permisos_agrupados = 'SELECT *
        FROM permisos
        WHERE borrado LIKE "no"
        AND permiso LIKE "datos-agrupadores%"
        AND alias LIKE "'.$_SESSION['alias_en_sesion'].'"
        ORDER BY permiso ASC';

    $query_permisos_agrupados = $conexion->prepare($consulta_permisos_agrupados);
    $query_permisos_agrupados->execute();

    while($rows_pa = $query_permisos_agrupados->fetch(PDO::FETCH_ASSOC))
    {
        $partes_del_permiso = explode("-", $rows_pa['permiso']);

        if(isset($partes_del_permiso[3]))
        {
            $partes_del_agrupador = explode("*", $partes_del_permiso[3]);
            foreach ($partes_del_agrupador as $pos => $agrupador_permitido) $agrupadores_permitidos[$agrupador_permitido] = "permitido";
        }
    }
}

    if(!isset($valor)) $valor = "";

        $datos_a_usar = explode('-', $campo_atributo['herramientas']);
        $tabla_a_usar = $datos_a_usar[0];
        $campo_a_usar = $datos_a_usar[1];
        
        $consulta_seleccion = 'SELECT '.$campo_a_usar.', borrado
            FROM '.$tabla_a_usar.'
            WHERE borrado LIKE "no" 
            GROUP BY '.$campo_a_usar.'
            ORDER BY '.$campo_a_usar.'
            ASC';

        $query_seleccion = $conexion->prepare($consulta_seleccion);
        $query_seleccion->execute();

        echo '<select id="'.$campo_nombre.'-select" value="'.$valor.'">';
            echo '<option value=""></option>';

        while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC))
        {
            if(isset($agrupadores_permitidos[$rows_seleccion[$campo_a_usar]]) or isset($agrupadores_permitidos['todos']))
            {
                if($rows_seleccion[$campo_a_usar] === $valor)
                {
                    echo '<option value="'.$rows_seleccion[$campo_a_usar].'"selected>'.ucwords($rows_seleccion[$campo_a_usar]).'</option>';
                }
                else
                {
                    echo '<option value="'.$rows_seleccion[$campo_a_usar].'">'.ucwords($rows_seleccion[$campo_a_usar]).'</option>';
                }
            }
        }
        echo '</select>';

        echo '<input type="text" id="'.$campo_nombre.'-texto" value="'.$valor.'">';
    
        echo '<input type="hidden" id="'.$campo_nombre.'-oculto" name="'.$campo_nombre.'" value="'.$valor.'">';

    echo '</td>';

    echo '<td>';
        if(isset($agrupadores_permitidos['nuevos'])) echo 'Nuevo? <input type="checkbox" id="'.$campo_nombre.'-modo-de-insercion" name="'.$campo_nombre.'-modo-de-insercion" value="opcion-texto">';

?>

<script type="text/javascript">

//no creo que funcione cuando se usa mas de una vez en un solo documento... por el nombre de la variable? se va a reescribir?

    checkbox_a_usar = '<?php echo $campo_nombre; ?>'+'-modo-de-insercion';
    input_de_texto = '<?php echo $campo_nombre; ?>'+'-texto';
    input_de_select = '<?php echo $campo_nombre; ?>'+'-select';
    input_final = '<?php echo $campo_nombre; ?>'+'-oculto';

    document.getElementById(input_de_texto).style.display = 'none';

    $('#'+input_de_select).change(function()
    {
         valor_a_grabar = document.getElementById(input_de_select).value;
         document.getElementById(input_final).value = valor_a_grabar;
    });


    $('#'+input_de_texto).keyup(function()
    {
         valor_a_grabar = document.getElementById(input_de_texto).value;
         document.getElementById(input_final).value = valor_a_grabar;
    });


    $('#'+checkbox_a_usar).change(function()
    {
        var esta_chequeado = $('#'+checkbox_a_usar).is(':checked');

        if(esta_chequeado)
        {
            document.getElementById(input_de_select).style.display = 'none';
            document.getElementById(input_de_texto).style.display = 'initial';

            valor_a_grabar = document.getElementById(input_de_texto).value;
        }
        else
        {
            document.getElementById(input_de_texto).style.display = 'none';
            document.getElementById(input_de_select).style.display = 'initial';

            valor_a_grabar = document.getElementById(input_de_select).value;
        }

        document.getElementById(input_final).value = valor_a_grabar;
    });

</script>
