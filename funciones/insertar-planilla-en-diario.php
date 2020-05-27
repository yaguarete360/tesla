<?php if(!isset($_SESSION)) {session_start();}


echo '<form action="" method="post">';
    echo '<table id="tabla_de_la_planilla">';

        if(!isset($_POST['paso_1']) and !isset($_POST['insertar']))
        {
            echo '<tr>';
                echo '<td>';
                    echo '<h3>'.strtoupper($planilla_nombre).'</h3>';
                    echo '<input type="hidden" name="planilla_nombre" value="'.$planilla_nombre.'"/>';
                echo '</td>';
                echo '<td>';
                    echo '<b>&nbsp-&nbsp</b>';
                echo '</td>';
                echo '<td>';
                    $periodo_ano = date('Y');
                    echo '<input type="text" name="planilla_ano" value="'.$periodo_ano.'" placeholder="Planilla A単o"/>';
                echo '</td>';
                echo '<td>';
                    echo '<b>&nbsp-&nbsp</b>';
                echo '</td>';
                echo '<td colspan="">';
                    $meses_s = ",enero,febrero,marzo,abril,mayo,junio,julio,agosto,setiembre,octubre,noviembre,diciembre";
                    $meses_a = explode(",", $meses_s);
                    echo '<select name="planilla_mes"/>';
                        (isset($_POST['planilla_mes'])) ? $mes_seleccionado = $_POST['planilla_mes'] : $mes_seleccionado = date('m');
                        foreach ($meses_a as $num => $mes) if(!empty($mes)) echo '<option value="'.str_pad($num, 2, "0", STR_PAD_LEFT).'" '.(($num == $mes_seleccionado) ? 'selected': '').'>'.ucwords($mes).'</option>';
                    echo '</select>';
                echo '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td colspan="5">';
                    echo '<input type="submit" name="paso_1" value="Siguiente">';
                echo '</td>';
            echo '</tr>';
        }
        elseif(isset($_POST['paso_1']))
        {
            echo '<tr>';
                echo '<th style="padding:5px;">';
                    $planilla_a_usar = $_POST['planilla_nombre']."-".$_POST['planilla_ano']."-".$_POST['planilla_mes'];
                    echo '<h3>'.$planilla_a_usar.'</h3>';
                    echo '<input type="hidden" name="planilla_a_usar" value="'.$planilla_a_usar.'">';
                echo '</th>';
            echo '</tr>';
            
            echo '<tr>';
                foreach ($campos_a_insertar as $campo_nombre => $campo_atributo)
                {
                    echo '<th style="padding:5px;">';
                        if(!isset($campo_atributo['valor'])) echo $campo_nombre;
                    echo '</th>';
                }
            echo '</tr>';

            echo '<tr>';
                $indice=0;
                foreach ($campos_a_insertar as $campo_nombre => $campo_atributo)
                {
                    $variable_nombre = "";
                    $estilo_extra = "";
                    if(isset($campo_atributo['valor']) or ($campo_atributo['asistente'] == "numero")) $estilo_extra = "text-align:right;";
                    echo '<td id="'.$campo_nombre.'" style="padding:5px;'.$estilo_extra.'">';
                        if(isset($campo_atributo['valor']))
                        {
                            echo '<input type="hidden" value="'.$campo_atributo['valor'].'">';
                        }
                        else
                        {
                            $valor = "";
                            $rotulo = "";
                            switch ($campo_atributo['asistente'])
                            {
                                case 'fecha':
                                    include '../../funciones/seleccionar-fechas.php';
                                break;

                                case 'opcion-especifica':
                                    $sin_datos = "no";
                                    $blanco = "si";
                                    $todos = "no";
                                    if(isset($campo_atributo['pre_definido'])) $valor = $campo_atributo['pre_definido'];
                                    include '../../funciones/seleccionar-archivos-especificos.php';
                                break;

                                case 'opcion-especifica-simplificado':
                                    include '../../funciones/seleccionar-archivos-especificos-simplificado.php';
                                break;

                                case 'traer_numero_de_cuenta':
                                    $cuenta_numeros = array();
                                    $campo_de_cuenta = explode("-", $campo_atributo['herramientas']);
                                    $consulta_cuenta_numero = 'SELECT * 
                                        FROM cuentas
                                        WHERE borrado LIKE "no"
                                        AND '.$campo_atributo['herramientas'].' != "0"
                                        ORDER BY cuenta
                                        ';
                                    $query_cuenta_numero = $conexion->prepare($consulta_cuenta_numero);
                                    $query_cuenta_numero->execute();
                                    while($rows_cuenta_numero = $query_cuenta_numero->fetch(PDO::FETCH_ASSOC)) $cuenta_numeros[$rows_cuenta_numero['cuenta']] = $rows_cuenta_numero[$campo_atributo['herramientas']];
                                    $primer_valor_de_cuentas = "sin numero";
                                    echo '<span id="cuenta_numero">'.$primer_valor_de_cuentas.'</span>';
                                    echo '<input type="hidden" value="'.$primer_valor_de_cuentas.'">';
                                break;
                                
                                case 'feretros_traer_numeros_de_series':
                                    $cantidad_de_contenedores = 10;
                                    $consulta_feretros_series = 'SELECT * FROM feretros WHERE borrado LIKE "no" AND ';
                                    $este_periodo = $_POST['planilla_ano'].'-'.$_POST['planilla_mes'];
                                    if($campo_atributo['herramientas'] == "entrada_zona_03")
                                    {
                                        $consulta_feretros_series.= "(";
                                        for ($contenedor_num=1; $contenedor_num < $cantidad_de_contenedores+1; $contenedor_num++) $consulta_feretros_series.= $campo_atributo['herramientas'].'_contenedor_'.str_pad($contenedor_num, 2, "0", STR_PAD_LEFT).' LIKE "'.$este_periodo.'%" OR ';
                                        $consulta_feretros_series = rtrim($consulta_feretros_series, "OR ").") ";
                                    }
                                    else
                                    {
                                        $consulta_feretros_series.= $campo_atributo['herramientas'].' LIKE "'.$este_periodo.'%" ';
                                    }
                                    $consulta_feretros_series.= 'ORDER BY serie ASC';
                                    $query_fs = $conexion->prepare($consulta_feretros_series);
                                    $query_fs->execute();
                                    while($rows_fs = $query_fs->fetch(PDO::FETCH_ASSOC)) $feretro_series[] = $rows_fs['serie'];
                                    echo '<select>';
                                        echo '<option value=""></option>';
                                        foreach ($feretro_series as $pos => $serie) echo '<option value="'.$serie.'">'.$serie.'</option>';
                                    echo '</select>';
                                break;

                                case 'servicios_traer_codigos':
                                    $este_periodo = $_POST['planilla_ano'].$_POST['planilla_mes'];
                                    $consulta_difuntos_codigos = 'SELECT * FROM difuntos
                                        WHERE borrado LIKE "no"
                                        AND codigo LIKE "%-'.$este_periodo.'%"
                                        ORDER BY codigo ASC';
                                    $query_dc = $conexion->prepare($consulta_difuntos_codigos);
                                    $query_dc->execute();
                                    while($rows_dc = $query_dc->fetch(PDO::FETCH_ASSOC)) $difuntos_codigos[] = $rows_dc['codigo'];
                                    echo '<select>';
                                        echo '<option value=""></option>';
                                        foreach ($difuntos_codigos as $pos => $codigo) echo '<option value="'.$codigo.'">'.$codigo.'</option>';
                                    echo '</select>';
                                break;

                                case 'texto':
                                    (isset($campo_atributo['pre_definido'])) ? $valor = $campo_atributo['pre_definido'] : $valor = "";
                                    echo '<input type="text" value="'.$valor.'">';
                                break;

                                case 'numero':
                                    $estilo_del_input = 'style="width:100px;"';
                                    if($campo_nombre == "cuota") $estilo_del_input = 'style="width:55px;" id="input_cuota"';
                                    echo '<input type="number" value="" '.$estilo_del_input.'>';
                                break;
                                
                                case 'vista':
                                    (isset($campo_atributo['pre_definido'])) ? $valor = $campo_atributo['pre_definido'] : $valor = "";
                                    ($campo_nombre == "cuota") ? $estilo_del_input = 'style="width:55px;" id="input_cuota"' : $estilo_del_input = "";
                                    echo '<input type="text" '.$estilo_del_input.' value="'.$valor.'" readonly>';
                                break;

                                default:
                                    echo 'falta caso switch';
                                break;
                            }
                        }
                    echo '</td>';
                }
                echo '<td>';
                    echo '<img class="boton_persona_mas" src="../../imagenes/iconos/boton-altas.png" width="30px">Agregar';
                echo '</td>';
            echo '</tr>';

            echo '<tr>';
                echo '<td style="padding:5px;">';
                    echo '<input type="submit" name="insertar" value="Finalizar Planilla">';
                echo '</td>';
            echo '</tr>';
        }
        elseif(isset($_POST['insertar']))
        {
            if(isset($_POST['valores_a_insertar']))
            {
                echo '<tr>';
                    echo '<th style="padding:5px;">';
                        echo "Diario";
                    echo '</th>';
                    foreach ($_POST['valores_a_insertar']["0"] as $nombre => $valor) echo '<th style="padding:5px;">'.$nombre.'</th>';
                echo '</tr>';

                $planilla_partes = explode("-", $_POST['planilla_a_usar']);
                $planilla_fecha = $planilla_partes[1]."-".$planilla_partes[2];
                foreach ($_POST['valores_a_insertar'] as $pos => $campos)
                {
                    $fecha_de_comision_partes = explode("-", $campos['fecha']);
                    $fecha_de_comision = $fecha_de_comision_partes[0]."-".$fecha_de_comision_partes[1];
                    
                    if($fecha_de_comision == $planilla_fecha)
                    {
                        $_POST['valores_a_insertar'][$pos]['planilla'] = $_POST['planilla_a_usar'];
                    }
                    else
                    {
                        $_POST['valores_a_insertar'][$pos]['planilla'] = $planilla_partes[0]."-".$fecha_de_comision;
                    }

                    $_POST['valores_a_insertar'][$pos]['creado'] = date('Y-m-d');
                    $_POST['valores_a_insertar'][$pos]['borrado'] = "no";
                    $_POST['valores_a_insertar'][$pos]['usuario'] = $_SESSION['usuario_en_sesion'];
                }

                foreach ($_POST['valores_a_insertar'] as $pos => $campos)
                {
                    $ultimo_numero = date('Y').'-0000000';
                    $consulta_autonumeracion = 'SELECT * FROM diario WHERE borrado LIKE "no" AND diario LIKE "'.date('Y').'-%" ORDER BY diario DESC LIMIT 1';
                    $query_an = $conexion->prepare($consulta_autonumeracion);
                    $query_an->execute();
                    while($rows_an = $query_an->fetch(PDO::FETCH_ASSOC)) $ultimo_numero = $rows_an['diario'];
                    $numero_a_usar = explode("-", $ultimo_numero);
                    $ultimo_numero = date('Y').'-'.str_pad($numero_a_usar[1] + 1, 7, "0", STR_PAD_LEFT);

                    $consulta_insercion = 'INSERT INTO diario (diario,';
                    foreach ($campos as $nombre => $valor) $consulta_insercion.= $nombre.', ';
                    $consulta_insercion = rtrim($consulta_insercion, ', ').') VALUES ("'.$ultimo_numero.'",';
                    foreach ($campos as $nombre => $valor) (empty($valor)) ? $consulta_insercion.= '"sin datos", ' : $consulta_insercion.= '"'.$valor.'", ';
                    $consulta_insercion = rtrim($consulta_insercion, ', ').')';
                    $query_insercion = $conexion->prepare($consulta_insercion);
                    $query_insercion->execute();

                    echo '<tr>';
                        echo '<td style="padding:5px;text-align:right;">';
                            echo $ultimo_numero;
                        echo '</td>';
                        foreach ($campos as $nombre => $valor)
                        {
                            (is_numeric($valor)) ? $estilo_extra = "text-align:right;" : $estilo_extra = "";
                            echo '<td style="padding:5px;'.$estilo_extra.'">';
                                echo (is_numeric($valor)) ? number_format($valor): $valor;
                            echo '</td>';
                        }
                    echo '</tr>';
                }
            }
            else
            {
                echo "<b>no hay datos que insertar.</b>";
            }
        }
        else
        {
            echo "<b>ERROR</b>";
        }

    echo '</table>';
echo '</form>';

?>

<script type="text/javascript">
    
    var cuenta_numeros = '<?php if(isset($cuenta_numeros)) echo json_encode($cuenta_numeros); ?>';
    if(cuenta_numeros) var cuenta_numeros = JSON.parse(cuenta_numeros);

    var contador_vueltas = 0;
    $(".boton_persona_mas").click(function()
    {
        var esta_fila = $(this).closest('tr');
        
        var cuota = esta_fila.find('td').find('#input_cuota').val();
        cuota_vueltas = +cuota + 1;
        for (var i = 1; i < cuota_vueltas; i++)
        {
            esta_fila.before('<tr></tr>');
            esta_fila.find('input,select').each(function(){
                
                var este_valor = $(this).val();
                var este_tipo = $(this).attr('type');
                var este_nombre = $(this).closest('td').attr('id');
                var este_estilo = $(this).closest('td').attr('style');
                
                if(este_tipo == "hidden" && este_nombre != "cuenta_numero")
                {
                    var celda_a_insertar = esta_fila.prev().append('<td style="'+este_estilo+'"><input type="hidden" name="valores_a_insertar['+contador_vueltas+']['+este_nombre+']" value="'+este_valor+'"></td>');
                }
                else
                {
                    if(este_nombre == "fecha")
                    {
                        var esta_fecha = new Date( este_valor );
                        esta_fecha.setMonth( esta_fecha.getMonth( ) - 1 + i );
                        esta_fecha.setDate( esta_fecha.getDate( ) + 1);
                        este_valor = esta_fecha.getFullYear( ) + '-' + ('00'+( esta_fecha.getMonth( ) + 1)).substr(-2) + '-' + ('00'+esta_fecha.getDate( )).substr(-2);
                    }
                    if(este_nombre == "cuenta") var cuenta_a_usar = este_valor;
                    if(este_nombre == "cuenta_numero") este_valor = cuenta_numeros[cuenta_a_usar];
                    if(este_nombre == "cuota") este_valor = i+" de "+cuota;
                    if(este_valor){}else
                    {
                        este_valor = "sin datos";
                    }
                    
                    var celda_a_insertar = esta_fila.prev().append('<td style="'+este_estilo+'">'+este_valor+'<input type="hidden" name="valores_a_insertar['+contador_vueltas+']['+este_nombre+']" value="'+este_valor+'"></td>');
                }
            });
            contador_vueltas++;
            var insertar_boton = esta_fila.prev().append('<td><img class="boton_persona_menos" src="../../imagenes/iconos/boton-bajas.png" width="30px"></td>');
        };

        $(".boton_persona_menos").click(function()
        {
            $(this).closest('tr').remove();
        });

    });
    


</script>
