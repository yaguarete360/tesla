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

    echo '<select id="'.$campo_nombre.'-select" name="'.$campo_nombre.'" value="'.$valor.'">';
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
            $agrupadores_finales[$rows_seleccion[$campo_a_usar]] = "si";
        }
    }
    echo '</select>';

    echo '<input type="hidden" id="'.$campo_nombre.'-texto" name="'.$campo_nombre.'" value="'.$valor.'" disabled>';

    if($tabla_a_usar == "agrupadores")
    {
        $cantidad_de_datos = 10;

        foreach ($agrupadores_permitidos as $agrupador => $permitido)
        {
            if(isset($agrupadores_permitidos['todos']))
            {
                $consulta_seleccion = 'SELECT *
                    FROM agrupadores
                    WHERE borrado LIKE "no" 
                    AND descripcion LIKE "nombres de columnas"
                    ORDER BY '.$campo_a_usar.'
                    ASC';
            }
            else
            {
                $consulta_seleccion = 'SELECT *
                    FROM agrupadores
                    WHERE borrado LIKE "no" 
                    AND descripcion LIKE "nombres de columnas"
                    AND agrupador LIKE "'.$agrupador.'"
                    ORDER BY '.$campo_a_usar.'
                    ASC';
            }

            $query_seleccion = $conexion->prepare($consulta_seleccion);
            $query_seleccion->execute();
            while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC))
            {
                for ($i=1; $i <= $cantidad_de_datos; $i++) $rotulos_de_agrupadores[$rows_seleccion['agrupador']]["dato_".$i] = $rows_seleccion['dato_'.$i];
            }
        }
    }
echo '</td>';

echo '<td>';
    if(isset($agrupadores_permitidos['nuevos'])) echo 'Nuevo? <input type="checkbox" id="'.$campo_nombre.'-modo-de-insercion" name="'.$campo_nombre.'-modo-de-insercion" value="opcion-texto">';

?>

<script type="text/javascript">

    var rotulos_de_agrupadores = '<?php echo json_encode($rotulos_de_agrupadores); ?>';
    var rotulos_de_agrupadores = JSON.parse(rotulos_de_agrupadores);

    $('#'+'<?php echo $campo_nombre; ?>'+'-select').change(function()
    {
        var este_agrupador = this.value;
        
        var estos_rotulos = rotulos_de_agrupadores[este_agrupador];

        if(!estos_rotulos)
        {
            var estos_rotulos = {};
            var agrupador_de_referencia = Object.keys(rotulos_de_agrupadores)[0];
            $.each(rotulos_de_agrupadores[agrupador_de_referencia], function(index) {
                estos_rotulos[index] = index;
            });
        }
        
        $.each(estos_rotulos, function(index,value) {
            if(value && value != "no aplicable")
            {
                $('#td-label-'+index+' label').html(value);
                $('#'+index).prop('type', 'text');
                $('#'+index).prop('value', '');
            }
            else
            {
                $('#td-label-'+index+' label').html("");
                $('#'+index).prop('type', 'hidden');
                $('#'+index).prop('value', 'no aplicable');
            }
        });
    });


    $('#'+'<?php echo $campo_nombre; ?>'+'-modo-de-insercion').change(function()
    {
        if($(this).prop('checked'))
        {
            var estos_rotulos = {};
            var agrupador_de_referencia = Object.keys(rotulos_de_agrupadores)[0];
            $.each(rotulos_de_agrupadores[agrupador_de_referencia], function(index) {
                estos_rotulos[index] = index;
            });
            $('#'+'<?php echo $campo_nombre; ?>'+'-select').prop('disabled', true);
            $('#'+'<?php echo $campo_nombre; ?>'+'-select').css('display', 'none');

            $('#'+'<?php echo $campo_nombre; ?>'+'-texto').prop('disabled', false);
            $('#'+'<?php echo $campo_nombre; ?>'+'-texto').prop('type', 'text');
        }
        else
        {

            var este_agrupador = $('#'+'<?php echo $campo_nombre; ?>'+'-select').prop('value');
            var estos_rotulos = rotulos_de_agrupadores[este_agrupador];
        
            $('#'+'<?php echo $campo_nombre; ?>'+'-select').prop('disabled', false);
            $('#'+'<?php echo $campo_nombre; ?>'+'-select').css('display', 'initial');
            
            $('#'+'<?php echo $campo_nombre; ?>'+'-texto').prop('disabled', true);
            $('#'+'<?php echo $campo_nombre; ?>'+'-texto').prop('type', 'hidden');
        }

        $.each(estos_rotulos, function(index,value) {
            if(value && value != "no aplicable")
            {
                $('#td-label-'+index+' label').html(value);
                $('#'+index).prop('type', 'text');
                $('#'+index).prop('value', '');
            }
            else
            {
                $('#td-label-'+index+' label').html("");
                $('#'+index).prop('type', 'hidden');
                $('#'+index).prop('value', 'no aplicable');
            }
        });
    });

</script>
