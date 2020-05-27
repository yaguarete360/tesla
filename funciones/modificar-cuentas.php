<?php if (!isset($_SESSION)) {session_start();}
    
    $campos_del_sistema = array(
        'id',
        'cuenta',
        'cliente',
        'proveedor',
        'funcionario',
        'fiscal',
        'accionista',
        'asociacion',
        'documento_tipo',
        'documento_numero',
        'forma_de_pago_predeterminado',
        'porcentaje',
        'origen',
        'usuario',
        'creado',
        'modificado',
        'borrado',
        'migracion'
    );
    if(isset($campos_de_cuentas) and !is_array($campos_de_cuentas) and $campos_de_cuentas == 'todos')
    {
        $campos_de_cuentas = array();
        $consulta_estructura = 'SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME LIKE "cuentas" AND TABLE_SCHEMA LIKE "'.$_SESSION['dbNombre'].'"';
        $query_estructura = $conexion->prepare($consulta_estructura);
        $query_estructura->execute();
        while($rows_estructura = $query_estructura->fetch(PDO::FETCH_ASSOC))
        {
            $campos_de_cuentas[] = $rows_estructura['COLUMN_NAME'];
        }
    }

    if(isset($_POST['actualizar_cuentas']))
    {
        foreach ($_POST['actualizar'] as $cuenta => $campos_a_actualizar)
        {
            $consulta_actualizar = 'UPDATE cuentas SET ';
            foreach ($campos_a_actualizar as $campo_nombre => $campo_valor)
            {
                $campo_valor = (empty($campo_valor)) ? 'sin datos' : $campo_valor;
                $consulta_actualizar.= $campo_nombre.' = "'.strtolower($campo_valor).'", ';
            }
            $consulta_actualizar = rtrim($consulta_actualizar, ', ').'
                WHERE borrado LIKE "no"
                AND '.$tipo_de_cuenta.' > 0
                AND cuenta LIKE "'.$cuenta.'"
                AND id = "'.$_POST['cuentas_id'][$cuenta].'"';
            $query_actualizar = $conexion->prepare($consulta_actualizar);
            $query_actualizar->execute();
        }
    }

    echo '<form method="post" action="">';
        echo '<table>';
            echo '<tr>';
                echo '<td>';
                    echo 'Cuenta';
                echo '</td>';
                echo '<td colspan="10">';
                    $requerido = '';
                    $campo_nombre = 'cuenta_a_buscar';
                    $campos_filtro = array();
                    $campo_atributo['herramientas'] = 'cuentas-cuenta-proveedor!=0';
                    $herramientas_explotado = explode("-", $campo_atributo['herramientas']);
                    $tabla_a_usar = $herramientas_explotado[0];
                    $campo_a_usar = $herramientas_explotado[1];
                    $herramientas_sub_explotado = explode("#", $herramientas_explotado[2]);
                    foreach ($herramientas_sub_explotado as $pos => $herramientas_sub) $campos_filtro[] = $herramientas_sub;
                    include '../../funciones/autocompletar-base.php';
                echo '</td>';
                echo '<td>';
                    echo '<input type="submit" name="buscar_proveedor" value="Buscar Proveedor">&nbsp&nbsp';
                echo '</td>';
            echo '</tr>';
        echo '</table>';
        echo '<br/>';

        if((isset($_POST['buscar_proveedor']) or isset($_POST['actualizar_cuentas'])) and !empty($_POST['cuenta_a_buscar']))
        {
            $bancos[] = '';
            $consulta_bancos = 'SELECT descripcion FROM agrupadores
                WHERE borrado LIKE "no"
                AND agrupador LIKE "bancos"
                AND descripcion NOT LIKE "nombres de columnas"
                ORDER BY descripcion ASC';
            $query_bancos = $conexion->prepare($consulta_bancos);
            $query_bancos->execute();
            while($rows_bancos = $query_bancos->fetch(PDO::FETCH_ASSOC)) $bancos[] = $rows_bancos['descripcion'];

            $consulta_cuentas_control = 'SELECT count(id) AS contador_control, cuenta FROM cuentas
                WHERE borrado LIKE "no"
                    AND '.$tipo_de_cuenta.' > 0
                    AND cuenta LIKE "%'.$_POST['cuenta_a_buscar'].'%"
                GROUP BY cuenta
                ORDER BY cuenta ASC';
            $query_cuentas_control = $conexion->prepare($consulta_cuentas_control);
            $query_cuentas_control->execute();
            while($rows_cuentas_control = $query_cuentas_control->fetch(PDO::FETCH_ASSOC))
            {
                $cuentas_controles[$rows_cuentas_control['cuenta']] = ($rows_cuentas_control['contador_control'] > 1) ? $rows_cuentas_control['contador_control'] : 'ok';
            }

            $consulta_cuentas = 'SELECT id, cuenta, '.implode(', ', $campos_de_cuentas).' FROM cuentas
                WHERE borrado LIKE "no"
                    AND '.$tipo_de_cuenta.' > 0
                    AND cuenta LIKE "%'.$_POST['cuenta_a_buscar'].'%"
                ORDER BY cuenta ASC';
            $query_cuentas = $conexion->prepare($consulta_cuentas);
            $query_cuentas->execute();
            echo '<table class="tabla_linda">';
                while($rows_cuentas = $query_cuentas->fetch(PDO::FETCH_ASSOC))
                {
                    $cuenta = $rows_cuentas['cuenta'];
                    if($cuentas_controles[$cuenta] == 'ok')
                    {
                        echo '<tr>';
                            echo '<th colspan="20">';
                                echo $cuenta;
                                echo '<input type="hidden" name="cuentas_id['.$cuenta.']" value="'.$rows_cuentas['id'].'">';
                            echo '</th>';
                        echo '</tr>';
                        foreach ($campos_de_cuentas as $pos => $campo_nombre) if(!in_array($campo_nombre, $campos_del_sistema))
                        {
                            echo '<tr>';
                                echo '<td>';
                                    echo $campo_nombre;
                                echo '</td>';
                                switch ($campo_nombre)
                                {
                                    case 'identidad_tipo':
                                        echo '<td>';
                                            echo '<input type="text" name="actualizar['.$cuenta.']['.$campo_nombre.']" value="'.$rows_cuentas[$campo_nombre].'">';
                                        echo '</td>';
                                    break;

                                    case 'cuenta_bancaria_1_banco':
                                    case 'cuenta_bancaria_2_banco':
                                    case 'cuenta_bancaria_3_banco':
                                        echo '<td>';
                                            echo '<select name="actualizar['.$cuenta.']['.$campo_nombre.']" style="width:100%">';
                                                foreach ($bancos as $banco)
                                                {
                                                    $seleccionado = ($banco == $rows_cuentas[$campo_nombre]) ? 'selected' : '';
                                                    echo '<option value="'.$banco.'" '.$seleccionado.'>'.ucwords($banco).'</option>';
                                                }
                                            echo '</select>';
                                        echo '</td>';
                                    break;
                                    
                                    default:
                                        echo '<td>';
                                            echo '<input type="text" name="actualizar['.$cuenta.']['.$campo_nombre.']" value="'.$rows_cuentas[$campo_nombre].'">';
                                        echo '</td>';
                                    break;
                                }
                            echo '</tr>';
                        }
                        echo '<tr style="background-color:white;"><td colspan="20">&nbsp</td></tr>';
                    }
                    else
                    {
                        if(!isset($cuentas_controladas[$cuenta]))
                        {
                            echo '<i>La cuenta <b>'.$cuenta.'</b> se repite '.$cuentas_controles[$cuenta].' veces. Ver con un programador para corregir.</i><br/>';
                        }
                        $cuentas_controladas[$cuenta] = 'si';
                    }
                }
            echo '</table>';
            echo '<input type="submit" name="actualizar_cuentas" value="Actualizar">';
        }
    echo '</form>';

?>
