<?php if (!isset($_SESSION)) {session_start();}
$cantidad_de_beneficiarios = 10;
$contador_fechas = 0;
$contratos_encontrados = 0;
$sum_total = 0;
$hoy = date("Y-m-d");
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

include '../../vistas/datos/ventas.php';

if(isset($_POST['siguiente']))
{
    $_SESSION['tipo_venta'] = $_POST['contrato_centro'];
	$contrato_centro_1 = $_POST['contrato_centro'];
	$datos_de_la_venta['contrato_centro'] = $contrato_centro_1;
	$consulta = 'SELECT * FROM ventas 
                 WHERE borrado LIKE "no" AND 
                 documento_tipo LIKE "%contrato '.$_POST['contrato_centro'].'%" AND 
                 documento_numero LIKE "%'.$_POST['contrato_numero'].'%" AND 
                 linea = "'.$_POST['contrato_linea'].'" ORDER BY documento_tipo';
	//echo $consulta;die();
	$query = $conexion->prepare($consulta);
	$query->execute();
	while($rows = $query->fetch(PDO::FETCH_ASSOC))
	{
		$contratos_encontrados++;
		foreach ($_SESSION['campos'] as $campo_nombre => $campo_atributo)
		    $datos_de_la_venta[$campo_nombre] = $rows[$campo_nombre];
            $_SESSION['ventas'] = $datos_de_la_venta;

            $consulta_cuenta = "SELECT * 
            					FROM `cuentas` 
            					WHERE cuenta LIKE '%".$_SESSION['ventas']['cliente']."%'";

			$query_cuenta = $conexion->prepare($consulta_cuenta);
			$query_cuenta->execute();
			while($rows_cuenta = $query_cuenta->fetch(PDO::FETCH_ASSOC))
			{
				$_SESSION['cuenta_cliente'] = $rows_cuenta['cliente'];
				$_SESSION['cuenta_documento_numero'] = $rows_cuenta['documento_numero'];
				
			}
	}

}

if($contratos_encontrados == 0)
{
	echo '<form id="form_principal" method="post" action="">';
		echo '<table>';
			echo '<tr>';
				echo '<td>';
					echo '<h4>Linea:&nbsp</h4>';
				echo '</td>';
				echo '<td>';
					echo '<select name="contrato_linea" id="contrato_linea">';
						$seleccionado = (isset($_POST['contrato_linea']) and $_POST['contrato_linea'] == "1") ? "selected" : "";
						echo '<option value="1" '.$seleccionado.'>Parque (1)</option>';
						$seleccionado = (isset($_POST['contrato_linea']) and $_POST['contrato_linea'] == "2") ? "selected" : "";
						echo '<option value="2" '.$seleccionado.'>Memorial (2)</option>';
					echo '</select>';
					echo '&nbsp&nbsp&nbsp';
				echo '</td>';
				echo '<td>';
					echo '<h4>Centro:&nbsp</h4>';
                echo '</td>';

                echo '<td>';

                    $prestaciones = "PSM,UDS,PSV,EXH,PSC,PSI,SDS";

                    $prestaciones = explode(",", $prestaciones);

                    natsort($prestaciones);

                    echo '<select name="contrato_centro" id ="contrato_centro">';
                    foreach ($prestaciones as $prestacion_vuelta => $prestacion_nombre)
                    {
                        if($prestacion_nombre == $prestacion_codigo_tipo)
                        {
                            echo '<option value="'.$prestacion_nombre.'" selected>'.ucwords($prestacion_nombre).'</option>';
                        }
                        else
                        {
                            echo '<option value="'.$prestacion_nombre.'">'.ucwords($prestacion_nombre).'</option>';
                        }
                    }
                    echo '</select>';
				echo '</td>';

                echo '<td>';
                echo '<h4>&nbsp;&nbsp;</h4>';
                echo '</td>';

                echo '<td>';
                echo '<h4>Numero:&nbsp</h4>';
                echo '</td>';

				echo '<td>';
					//echo '<input type="text" name="contrato_centro" id="contrato_centro" value="contrato '.$tipo_de_contrato.'">';
				echo '</td>';
				echo '<td>';
					$valor = (isset($_POST['contrato_numero'])) ? $_POST['contrato_numero'] : '';
					echo '<input type="number" name="contrato_numero" id="contrato_numero" value="'.$valor.'">';
				echo '</td>';
				echo '<td>';
					echo '<input type="submit" name="siguiente" value="Siguiente">';
				echo '</td>';
			echo '</tr>';
		echo '</table>';
	echo '</form>';
	if(isset($_POST['siguiente']))
	{
		echo '<span style="color:red;">
              <b>No se ha encontrado la venta del contrato buscado.</b>
        </span>';
	}
}

elseif($contratos_encontrados > 1)
{
	echo "Se encontraron ".$contratos_encontrados." ventas del mismo ".$_POST['contrato_centro']." ".$_POST['contrato_numero'];
	echo '<br/>';
	echo '<span style="color:red;">
    <b>ARREGLAR ANTES DE CONTINUAR</b>
    </span>';
}
else
{
    echo '<form id="form_secundario" method="post" action="">';
	echo '<table>';
		echo '<tr>';
			echo '<td colspan="20">';
				echo '<h3>';
				   
				    $dato_documento = explode(" ",$datos_de_la_venta['documento_tipo']);
				    $_SESSION['linea'] = $_POST['contrato_linea'];
					echo $datos_de_la_venta['cliente']." - ".
                        $_POST['contrato_linea']."-".$dato_documento[1]."-".
                        str_pad($datos_de_la_venta['documento_numero'], 7, "0", STR_PAD_LEFT)." - ".$datos_de_la_venta['producto'];

                        //CONSULTAR PRECIO MINIMO
                        if($_SESSION['linea']==1){
                        	$_SESSION['linea_varchar'] = "pse";
                        }else{
                        	$_SESSION['linea_varchar'] = "mem";
                        }

                        $producto_varchar = explode("-", $datos_de_la_venta['producto']);
                        $_SESSION['producto_varchar'] = $producto_varchar[1];
                        $_SESSION['producto_varchar'];
                        //echo "<br>".$_SESSION['producto_varchar'];die();
                        if(strtolower($_SESSION['producto_varchar'])=="pno")
						{
						    $producto_tipo = "platino";
						}
						if(strtolower($_SESSION['producto_varchar'])=="cel")
						{
						    $producto_tipo = "celestial";
						}

						if(strtolower($_SESSION['producto_varchar'])=="oro")
						{
						    $producto_tipo = "oro";
						}

						if(strtolower($_SESSION['producto_varchar'])=="plata" || strtolower($_SESSION['producto_varchar'])=="pla")
						{
						    $producto_tipo = "plata-1";
						}

						if(strtolower($_SESSION['producto_varchar'])=="sla")
						{
						    $producto_tipo = "plan a san lorenzo";
						}

						if(strtolower($_SESSION['producto_varchar'])=="slb")
						{
						    $producto_tipo = "plan b san lorenzo";
						}

						if(strtolower($_SESSION['producto_varchar'])=="sja")
						{
						    $producto_tipo = "plan a sajonia";
						}

						if(strtolower($_SESSION['producto_varchar'])=="sjb")
						{
						    $producto_tipo = "plan b sajonia";
						}


						if(strtolower($_SESSION['producto_varchar'])=="hom")
						{
						    $producto_tipo = "homenaje";
						}

						if(strtolower($_SESSION['producto_varchar'])=="hom")
						{
						    $producto_tipo = "celestial";
						}

                       if($_SESSION['tipo_venta']=="PSM"){
                           $year = date("Y");
                           $precio = "precios en la web ".$_SESSION['linea_varchar']." ".$dato_documento[1]."";
                           $select_precio_minimo = "SELECT * 
												 FROM agrupadores 
												 WHERE agrupador LIKE '%".$precio."%' AND 
												 descripcion LIKE '".$producto_tipo."' AND 
												 dato_1 = ".$year." AND
												 dato_3 = 'minimo'";
                           //echo $select_precio_minimo;die();
                           $query_precio_minimo = $conexion->prepare($select_precio_minimo);
                           $query_precio_minimo->execute();
                           $_SESSION['precio_minimo'] = 0;
                           while($rows_precio = $query_precio_minimo->fetch(PDO::FETCH_ASSOC))
                           {
                               $_SESSION['precio_minimo'] = str_replace(".", "", $rows_precio['dato_2']);
                           }
                       }

						
						//echo "<br>=========> PRECIO Min: ".$_SESSION['precio_minimo'];
				echo '</h3>';
			echo '</td>';
		echo '</tr>';


		  /******BENEFICIARIO SOLO SI ES PSM********/

            if($_SESSION['tipo_venta']=="PSI"){
                $cantidad_de_beneficiarios =3;
            }else{
                $cantidad_de_beneficiarios =1;
            }

            if($_SESSION['tipo_venta']=="PSM"){
                $cantidad_de_beneficiarios =10;
            }




                echo '<tr>';
                echo '<td colspan="20">';
                echo '<h4>';
                echo "Beneficiarios";
                echo '</h4>';
                echo '</td>';
                echo '</tr>';
                echo '<tr>';

                foreach ($campos_del_beneficiario as $campo_nombre => $asistente)
                {
                    echo '<td>';
                    switch ($campo_nombre)
                    {
                        case 'beneficiario_numero':
                            echo "#";
                            break;

                        default:
                            if($campo_nombre=="monto_cuota" || $campo_nombre=="edad"){
                                if($_SESSION['tipo_venta']=="PSM"){
                                    echo ucwords(str_replace("_", " ", $campo_nombre));
                                }
                            }else{
                                echo ucwords(str_replace("_", " ", $campo_nombre));
                            }

                            break;
                    }
                    echo '</td>';
                }


                echo '</tr>';
                for ($i=1; $i <= $cantidad_de_beneficiarios; $i++)
                {
                    echo '<tr class="info_beneficiario_'.$i.'">';
                    foreach ($campos_del_beneficiario as $campo_nombre => $asistente)
                    {
                        $campo_atributo['herramientas'] = "";
                        echo '<td>';
                        switch ($campo_nombre)
                        {
                            case 'beneficiario_numero':
                                echo $i;
                                break;

                            case 'documento_tipo':
                                $variable_nombre = 'beneficiarios['.$i.']['.$campo_nombre.']';
                                $rotulo = "";
                                $valor = "";
                                $campo_atributo['herramientas'] = "agrupadores-descripcion-agrupador-tipos de documentos de identificacion";
                                include '../../funciones/seleccionar-archivos-especificos.php';
                                break;


                            case 'estado_civil':
                                $variable_nombre = 'beneficiarios['.$i.']['.$campo_nombre.']';
                                $valor = "";
                                $campo_atributo['herramientas'] = "estado";
                                include '../../funciones/seleccionar-botones.php';
                                break;

                            case 'sexo':
                                echo '<select name="beneficiarios['.$i.']['.$campo_nombre.']">';
                                echo '<option value="masculino">Masc.</option>';
                                echo '<option value="femenino">Fem.</option>';
                                echo '</select>';
                                break;

                            case 'nacimiento': // case 'defuncion':
                                $variable_nombre = 'beneficiarios['.$i.']['.$campo_nombre.']';
                                // $rotulo = "";
                                // $indice = $contador_fechas;
                                // include '../../funciones/seleccionar-fechas.php';
                                // $contador_fechas++;
                                echo '<input type="date" class="'.$campo_nombre.'" name="'.$variable_nombre.'">';
                                break;


                            case 'monto_cuota': case 'vigencia':
                            if($_SESSION['tipo_venta']=="PSM"){
                                echo '<input type="text" id="'.$campo_nombre.'" class="'.$campo_nombre.'" name="beneficiarios['.$i.']['.$campo_nombre.']" readonly>';

                            }
                            break;

                            case 'edad':
                                // echo '<input type="text" id="'.$campo_nombre.'" name="beneficiarios['.$i.']['.$campo_nombre.']" readonly>';
                                $variable_nombre = 'beneficiarios['.$i.']['.$campo_nombre.']';
                                $tipo_de_calculo = "hoy";
                                $campo_de_nacimiento = 'nacimiento';

                                $valor = (isset($rows[$campo_nombre])) ? $rows[$campo_nombre] : "";
                                if($_SESSION['tipo_venta']=="PSM"){
                                    echo '<input type="text" name="'.$variable_nombre.'" class="edad" value="'.$valor.'" readonly style="width:50px;"/>';

                                }
                                 break;

                            default:
                                echo '<input type="text" id="'.$campo_nombre.'" name="beneficiarios['.$i.']['.$campo_nombre.']">';
                                break;
                        }
                        echo '</td>';
                    }

                    echo '</tr>';
                }
                echo '<tr>';
                echo '<td colspan="10">';
                echo '&nbsp';
                echo '</td>';
                echo '</tr>';


		echo '<tr>';
			echo '<td colspan="10">';
				echo '<h4>';
					echo "Datos del Contrato";
				echo '</h4>';
			echo '</td>';
		echo '</tr>';

			foreach ($campos_del_contrato as $campo_nombre => $asistente)
			{
				echo '<tr>';
					echo '<td colspan="2">';
						echo $campo_nombre;
					echo '</td>';
					switch ($asistente)
					{
						case 'texto':
							echo '<td colspan="2">';
								echo '<input type="text" name="datos_del_contrato['.$campo_nombre.']" value="">';
							echo '</td>';
						break;

						case 'numero':
							echo '<td colspan="2">';
								echo '<input type="number" name="datos_del_contrato['.$campo_nombre.']" value="">';
							echo '</td>';
						break;

						case 'contrato_numero':
							echo '<td colspan="2">';
								echo $contrato_numero = $datos_de_la_venta['linea']."-".$datos_de_la_venta['contrato_centro']."-".str_pad($datos_de_la_venta['documento_numero'], 7, "0", STR_PAD_LEFT);
								echo '<input type="hidden" name="datos_del_contrato['.$campo_nombre.']" value="'.$contrato_numero.'">';
							echo '</td>';
						break;

						case 'traer_titular':
							echo '<td colspan="2">';
								echo $datos_de_la_venta['cliente'];
								echo '<input type="hidden" name="datos_del_contrato['.$campo_nombre.']" value="'.$datos_de_la_venta['cliente'].'">';
							echo '</td>';
						break;

						case 'opcion-especifica':
							echo '<td colspan="2">';
                                $rotulo = "";
								$variable_nombre = 'datos_del_contrato['.$campo_nombre.']';
								switch ($campo_nombre)
								{
									case 'cuenta_documento_tipo':
										$campo_atributo['herramientas'] = "agrupadores-descripcion-agrupador-tipos de documentos de identificacion";
									break;

									default:
										$campo_atributo['herramientas'] = "";
									break;
								}
								include '../../funciones/seleccionar-archivos-especificos.php';
							echo '</td>';
						break;

                        case 'opcion-pais':
                            echo '<td colspan="2">';
                            $rotulo = "";
                            $variable_nombre = 'datos_del_contrato['.$campo_nombre.']';
                            switch ($campo_nombre)
                            {

                                case 'direccion_pais':
                                    $valor = "paraguay";
                                    $campo_atributo['herramientas'] = "agrupadores-descripcion-agrupador-paises";
                                    break;

                                case 'contacto_pais':
                                    $campo_atributo['herramientas'] = "agrupadores-descripcion-agrupador-paises";
                                    break;

                                case 'direccion_declarada_titular_pais':
                                    $campo_atributo['herramientas'] = "agrupadores-descripcion-agrupador-paises";
                                    break;

                                case 'forma_de_pago':
                                    $campo_atributo['herramientas'] = "agrupadores-descripcion-agrupador-formas_de_pago_a_clientes";
                                    break;

                                case 'cobrador_nombre':
                                    $campo_atributo['herramientas'] = "agrupadores-descripcion-agrupador-cobradores_externos";
                                    break;

                                default:
                                    $campo_atributo['herramientas'] = "";
                                    break;
                            }
                            include '../../funciones/seleccionar-archivos-especificos.php';
                            echo '</td>';
                            break;

						case 'vista':
							echo '<td colspan="2">';
								switch ($campo_nombre)
								{
									case 'centro':
										$valor = $datos_de_la_venta['contrato_centro'];
									break;

									case 'fecha':
										$valor = date('Y-m-d');
									break;

									case 'numero':
										$valor = $datos_de_la_venta['documento_numero'];
									break;

									case 'linea': case 'factura': case 'vendedor': case 'producto': case 'solicitud':
										$valor = $datos_de_la_venta[$campo_nombre];
									break;

									default:
										$valor = "";
									break;
								}
								echo $valor;
								echo '<input type="hidden" name="datos_del_contrato['.$campo_nombre.']" value="">';
							echo '</td>';
						break;

						default:
							echo '<td colspan="2">';
								echo '<input type="hidden" name="datos_del_contrato['.$campo_nombre.']" value="">';
							echo '</td>';
						break;
					}
				echo '</tr>';
			}
    echo '<td>';
    echo '<input type="submit" name="procesar" value="Procesar">';
    echo '</td>';
	echo '</table>';

    echo '</form>';

}

if(isset($_POST['procesar']))
{
    /*INSERTAR BENEFICIARIO*/
    $no_existe=0;
    $total_cuota = 0;
    $monto_diferido = 0;
    $monto_diferido_calculado = 0;
    $cuota_a_generar = 0;
    $sum_total = 0;
    //SUM TOTAL CUOTA Y CANTIDAD DE CUOTAS A GENERAR
    for ($var=0; $var<=count($_POST['beneficiarios']);$var++)
    {
        if(!empty($_POST['beneficiarios'][$var]['beneficiario']))
        {
            if($_SESSION['tipo_venta']=="PSM"){
                $monto_diferido = $monto_diferido + str_replace(".","",$_POST['beneficiarios'][$var]['monto_cuota']);
                $sum_total = $sum_total + str_replace(".","",$_POST['beneficiarios'][$var]['monto_cuota']);

            }else{
                $monto_diferido = $_SESSION['ventas']['precio'];
                $sum_total = $sum_total + str_replace(".","",$_SESSION['ventas']['precio']);

            }
        	$mes_actual = date("m");
            $cuota_a_generar = 12 - $mes_actual;

            ##SI EL DIA ES MENOR AL 10 DE CADA MES GENERAR + 1 CUOTA
            $fecha_venta = $_SESSION['ventas']['fecha'];
            $fecha_venta = explode("-", $fecha_venta);
            $dia_venta = $fecha_venta[2];
            if($dia_venta<26){
                $cuota_a_generar  = $cuota_a_generar +1;
            }

        }
    }

    if($sum_total < @$_SESSION['precio_minimo']){
    	$monto_diferido = @$_SESSION['precio_minimo'];
    }

    $monto_diferido = $monto_diferido * $cuota_a_generar;

    for ($var=0; $var<=count($_POST['beneficiarios']);$var++)
    {
        if(!empty($_POST['beneficiarios'][$var]['beneficiario']))
        {
            //echo "NOMBRE: ".$_POST['beneficiarios'][$var]['beneficiario']."<br>";
            $dato_documento = explode(" ",$_SESSION['ventas']['documento_tipo']);
            $contrato = $_SESSION['linea']."-".$dato_documento[1]."-".
            str_pad($_SESSION['ventas']['documento_numero'], 7, "0", STR_PAD_LEFT);
            $_SESSION['contrato'] = $contrato;
            $sql_documento = "SELECT * FROM cuentas 
            WHERE cuenta like '".$_SESSION['ventas']['cliente']."'";

            $query_documento = $conexion->prepare($sql_documento);
            $query_documento->execute();
            while($rows_documento = $query_documento->fetch(PDO::FETCH_ASSOC))
            {
                $numero_documento = $rows_documento['documento_numero'];
                $documento_tipo = $rows_documento['identidad_tipo'];
                $cuenta_direccion_particular = $rows_documento['domicilio_ciudad']." - "
                .$rows_documento['domicilio_barrio']." - ".$rows_documento['domicilio_barrio'];
            }

            $mes_actual = date("m");
            $cuota_a_generar = 12 - $mes_actual;

            ##SI EL DIA ES MENOR AL 10 DE CADA MES GENERAR + 1 CUOTA
            $fecha_venta = $_SESSION['ventas']['fecha'];
            $fecha_venta = explode("-", $fecha_venta);
            $dia_venta = $fecha_venta[2];
            if($dia_venta<26){
                $cuota_a_generar  = $cuota_a_generar + 1;
            }

            $entrega_inicial = 0;

            $monto_cuota = 0;
            //echo "CUOTA: ".$monto_diferido." - ".$_SESSION['precio_minimo'];die();
            if($sum_total < @$_SESSION['precio_minimo']){
				$monto_cuota = @$_SESSION['precio_minimo'];
            }else{

                if($_SESSION['tipo_venta']=="PSM"){
                    $monto_cuota = str_replace(".","",$_POST['beneficiarios'][$var]['monto_cuota']);

                }else{
                    $monto_cuota = $_SESSION['ventas']['precio'];
                }
           }
            if($_SESSION['ventas']['ingreso_exonerado']=="si"){
            	$entrega_inicial = $monto_cuota;
            }

            if($_SESSION['ventas']['ingreso_exonerado']=="no"){
            	$entrega_inicial = $monto_cuota * 2;
           }
           
            $monto_diferido_calculado = $monto_diferido - $entrega_inicial;

            if($_SESSION['tipo_venta']=="PSM"){
                $bene_edad = $_POST['beneficiarios'][$var]['edad'];
                $monto_cuota_bene = $_POST['beneficiarios'][$var]['monto_cuota'];
            }else{
                $bene_edad = 0;
                $monto_cuota_bene = $_SESSION['ventas']['precio'];
            }
            $sql_insert = "INSERT INTO `contratos_migracion` (
                   `id`,
                   `fecha`,
                   `contrato`,
                   `contrato_linea`,
                   `contrato_centro`,
                   `contrato_numero`,
                   `producto`,
                   `cuenta`,
                   `cuenta_numero`,
                   `cuenta_documento_tipo`,
                   `cuenta_documento_numero`,
                   `cuenta_sexo`,
                   `cuenta_direccion_particular`,
                   `cuenta_particular_numero`,
                   `cuenta_particular_barrio`,
                   `cuenta_particular_pais`,
                   `cuenta_direccion_laboral`,
                   `cuenta_laboral_numero`,
                   `cuenta_laboral_barrio`,
                   `cuenta_laboral_pais`,
                   `cuenta_direccion_declarada_titular`,
                   `cuenta_declarada_titular_numero`,
                   `cuenta_declarada_titular_barrio`,
                   `cuenta_declarada_titular_pais`,
                   `cuenta_telefono`,
                   `beneficiario`,
                   `beneficiario_numero`,
                   `beneficiario_documento_tipo`,
                   `beneficiario_documento_numero`,
                   `beneficiario_nacimiento`,
                   `beneficiario_sexo`,
                   `beneficiario_estado_civil`,
                   `beneficiario_defuncion`,
                   `beneficiario_edad`,
                   `contacto_direccion`,
                   `contacto_numero`,
                   `contacto_barrio`,
                   `contacto_pais`,
                   `contacto_direccion_interseccion`,
                   `contacto_direccion_codigo_postal`,
                   `contacto_direccion_referencias`,
                   `contacto_telefono`,
                   `contacto_celular`,
                   `contacto_observaciones`,
                   `monto_diferido`,
                   `entrega_inicial`,
                   `cuotas_cantidad`,
                   `cuota_monto`,
                   `pre_vigencia`,
                   `caja_factura_numero`,
                   `caja_recibo_numero`,
                   `caja_monto`,
                   `asociacion`,
                   `asociacion_numero`,
                   `vencimiento_dia`,
                   `pagare_numero`,
                   `observaciones`,
                   `plazo_modificado`,
                   `plazo_actual`,
                   `datos_supervisor_de_ventas`,
                   `datos_gerente_de_ventas`,
                   `datos_base_de_datos`,
                   `datos_gerente_administrativo`,
                   `cobrador_numero`,
                   `datos_x`,
                   `creado`,
                   `modificado`,
                   `borrado`,
                   `usuario`,
                   `origen`,
                   `forma_de_pago`,
                   `cobrador_nombre`,`estado`) 
                   VALUES (NULL,
                   '".$_SESSION['ventas']['fecha']."',
                   '".$_SESSION['contrato']."',
                   '".$_SESSION['linea']."',
                   '".$dato_documento[1]."',
                   '".$_SESSION['ventas']['documento_numero']."',
                   '".$_SESSION['ventas']['producto']."',
                   '".$_SESSION['ventas']['cliente']."',
                   '".$_SESSION['cuenta_cliente']."',
                   '".$_POST['datos_del_contrato']['cuenta_documento_tipo']."',
                   '".$_POST['datos_del_contrato']['cuenta_documento_numero']."',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   '".$_POST['datos_del_contrato']['cuenta_direccion_declarada_titular']."',
                  'sin datos',
                  'sin datos',
                  'sin datos',
                   '".$_POST['datos_del_contrato']['telefono_numero_titular']."',
                   '".$_POST['beneficiarios'][$var]['beneficiario']."',
                   'bene num',
                   '".$_POST['beneficiarios'][$var]['documento_tipo']."',
                   '".$_POST['beneficiarios'][$var]['documento_numero']."',
                   '".$_POST['beneficiarios'][$var]['nacimiento']."',
                   '".$_POST['beneficiarios'][$var]['sexo']."',
                    '".$_POST['beneficiarios'][$var]['estado_civil']."',
                   '0000-00-00',
                   '".$bene_edad."',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'telefono contacto',
                   'celular contacto',
                   'obs contacto',
                   '".$monto_diferido_calculado."',
                   '".$entrega_inicial."',
                   ".$cuota_a_generar.",
                   '".str_replace(".","",$monto_cuota_bene)."',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   '".$hoy."',
                   '".$hoy."',
                    'no',
                    '".ucwords($_SESSION['usuario_en_sesion'])."', 
                    'sin datos',
                    '".$_POST['datos_del_contrato']['forma_de_pago']."',
                    '".$_POST['datos_del_contrato']['cobrador_nombre']."','vigente')";
            //echo $sql_insert;die();
            $save = $conexion->prepare($sql_insert);
            $save->execute();
            $total_cuota = $total_cuota + $monto_cuota;

        }
    }

    #####INSERTAR PULSOS TOTALIZADOS EN DIARIO
    if($_SESSION['tipo_venta']=="PSM"){
        $contrato = "contrato ".$dato_documento[1];
        insertarPulsosPSM($cuota_a_generar, $total_cuota, "PSM",
            $conexion,@$_POST['beneficiarios'][$var]['beneficiario'],
            $_POST['datos_del_contrato']['cuenta_documento_tipo'],
            $_POST['datos_del_contrato']['cuenta_documento_numero'],
            $contrato,
            @$_POST['beneficiarios'][$var]['sexo'],$mes_actual,$sum_total);
    }else{

        ##CONSULTAR ULTIMO NRO DE DOCUMENTO
        $sql_consultar_documento = "SELECT diario 
                                    FROM diario_migracion 
                                    ORDER BY id desc LIMIT 1";
        $resultado = $conexion->prepare($sql_consultar_documento);
        $resultado->execute();
        $documento = "2018-0";
        while($rows = $resultado->fetch(PDO::FETCH_ASSOC))
        {
            $documento = $rows['diario'];
        }

        $documento = explode("-",$documento);
        $documento = $documento[1] + 1;
        $documento = str_pad($documento,7,"0",STR_PAD_LEFT);

        #################################################################################
        $fecha = date("Y-m-d");
        $anho = date("Y");
        $documento = $anho."-".$documento;
        $planilla = "cuo-".$anho;
        $documento_numero = $_SESSION['ventas']['documento_numero'];

        $cantidad_de_cuotas = $_SESSION['ventas']['cuotas'];

        if(is_numeric($cantidad_de_cuotas)) {
            $cantidad_de_cuotas = $_SESSION['ventas']['cuotas'];
        }else{
            $cantidad_de_cuotas = 1;
        }

        $cuota_vencimiento = $fecha;
        for($int=0;$int<$cantidad_de_cuotas;$int++){

            $cuota_descripcion = ($int+1)." de ".$cantidad_de_cuotas;
            $cuota_vencimiento = strtotime ( '+30 day' , strtotime ( $cuota_vencimiento ) ) ;
            $cuota_vencimiento = date ( 'Y-m-j' , $cuota_vencimiento );
            $cuota_vencimiento = explode("-",$cuota_vencimiento);
            $cuota_vencimiento = $cuota_vencimiento[0]."-".$cuota_vencimiento[1]."-10";
            $monto = $_SESSION['ventas']['precio'];
            $contrato = $_SESSION['contrato'];

            $documento_tipo = $_POST['datos_del_contrato']['cuenta_documento_tipo'];
            $documento_numero = $_POST['datos_del_contrato']['cuenta_documento_numero'];

            $sql_insert = "INSERT INTO `diario_migracion` 
                    (`id`,
                    `diario`,
                    `planilla`,
                    `fecha`,
                    `numero_de_orden`,
                    `cuenta`,
                    `cuenta_numero`,
                    `cuenta_documento_tipo`,
                    `cuenta_documento_numero`,
                    `linea`,
                    `contrato`,
                    `documento_tipo`,
                    `documento_numero`,
                    `descripcion`,
                    `observacion`,
                    `pago_individual`,
                    `aprobado`,
                    `aprobado_por`,
                    `cantidad`,
                    `cuota`,
                    `cuota_vencimiento`,
                    `efectuado_fecha`,
                    `efectuado_por`,
                    `cuenta_bancaria_titular`,
                    `cuenta_bancaria_banco`,
                    `cuenta_bancaria_numero`,
                    `factura_tipo`,
                    `factura_numero`,
                    `iva_porcentaje`,
                    `iva_monto`,
                    `entra`,
                    `sale`,
                    `derecho`,
                    `obligacion`,
                    `cotizacion`,
                    `creado`,
                    `modificado`,
                    `borrado`,
                    `usuario`) 
                    VALUES (
                    NULL,
                    '".$documento."',
                    '".$planilla."',
                    '".$fecha."',
                    '0',
                    '".$_SESSION['ventas']['cliente']."',
                    '".$_SESSION['cuenta_cliente']."',
                    '".$documento_tipo."',
                    '".$documento_numero."',
                   '".$_SESSION['ventas']['linea']."',
                   '".$_SESSION['contrato']."',
                    '".$_SESSION['ventas']['documento_tipo']."',
                   '".$_SESSION['ventas']['documento_numero']."',
                    'generaciones por pulso',
                    'sin datos',
                    'sin datos',
                    '0000-00-00 00:00:00',
                    '1.00',
                    '0',
                    '".$cuota_descripcion."',
                    '".$cuota_vencimiento."',
                    '0000-00-00',
                    'sin datos',
                    'sin datos',
                    'sin datos',
                    'sin datos',
                    'sin datos',
                    'sin datos',
                    '0.00',
                    '0.00',
                    '1.00',
                    '0.00',
                    '".$monto."',
                    '0',
                    '0.00',
                    '".$hoy."',
                    '".$hoy."',
                    'no',
                    '".ucwords($_SESSION['usuario_en_sesion'])."')";
                $save = $conexion->prepare($sql_insert);
                $save->execute();

        }

    }

    echo "SE GUARDO CORRECTAMENTE!!";
}



function insertarPulsosPSM($cuota,$monto,$contrato,
                           $conexion,$beneficiario,
                           $documento_tipo,$documento_numero,
                           $contrato_centro,$sexo_beneficiario,
                           $mes_actual,$sum_total){
    $cuota = $cuota;
    $messiguiente = 1;
    $mescontinuo = 1;
    $añosiguiente = date('Y') + 1    .    '-';
    $añocontinuo = date('Y')    +    2    .    '-';
    $hoy = date("Y-d-m");
    for($ii = 1; $ii <= $cuota; $ii++)
    {
        $mes    =    date('m')    +    $ii;
        if( $mes    <=    9    )
        {
            $vencimiento    =    date('Y-')    .    '0'    .    $mes    .date('-d');
        }elseif(    $mes    >=    13    )
        {
            if($messiguiente    <=    9)
            {
                $vencimiento    =    $añosiguiente    .    '0'    .    $messiguiente    .date('-d');
                $messiguiente++;
            }elseif($messiguiente    >=    13)
            {
                $vencimiento    =    $añocontinuo    .    '0'    .    $mescontinuo    .date('-d');
                $mescontinuo++;
            }else{
                $vencimiento    =    $añosiguiente    .    $messiguiente    .date('-d');
                $messiguiente++;
            }
        }else{
            $vencimiento    =    date('Y-')    .    $mes    .date('-d');
        }

        ##CONSULTAR ULTIMO NRO DE DOCUMENTO
        $sql_consultar_documento = "SELECT diario 
                                    FROM diario_migracion 
                                    ORDER BY id desc LIMIT 1";
        $resultado = $conexion->prepare($sql_consultar_documento);
        $resultado->execute();
        $documento = "2018-0";
        while($rows = $resultado->fetch(PDO::FETCH_ASSOC))
        {
            $documento = $rows['diario'];
        }

        $documento = explode("-",$documento);
        $documento = $documento[1] + 1;
        $documento = str_pad($documento,7,"0",STR_PAD_LEFT);
        
        #################################################################################
        $fecha = date("Y-m-d");
        $anho = date("Y");
        $documento = $anho."-".$documento;
        $planilla = "cuo-".$anho."-".$mes;
        $cuota_descripcion = $ii." de ".$cuota;
        $cuota_vencimiento = $anho."-".$mes_actual."-"."10";

        if($sum_total < $_SESSION['precio_minimo']){
			$monto = $_SESSION['precio_minimo'];
        }
        $sql_insert = "INSERT INTO `diario_migracion` 
        (`id`,
        `diario`,
        `planilla`,
        `fecha`,
        `numero_de_orden`,
        `cuenta`,
        `cuenta_numero`,
        `cuenta_documento_tipo`,
        `cuenta_documento_numero`,
        `linea`,
        `contrato`,
        `documento_tipo`,
        `documento_numero`,
        `descripcion`,
        `observacion`,
        `pago_individual`,
        `aprobado`,
        `aprobado_por`,
        `cantidad`,
        `cuota`,
        `cuota_vencimiento`,
        `efectuado_fecha`,
        `efectuado_por`,
        `cuenta_bancaria_titular`,
        `cuenta_bancaria_banco`,
        `cuenta_bancaria_numero`,
        `factura_tipo`,
        `factura_numero`,
        `iva_porcentaje`,
        `iva_monto`,
        `entra`,
        `sale`,
        `derecho`,
        `obligacion`,
        `cotizacion`,
        `creado`,
        `modificado`,
        `borrado`,
        `usuario`) 
        VALUES (
        NULL,
        '".$documento."',
        '".$planilla."',
        '".$fecha."',
        '0',
        '".$_SESSION['ventas']['cliente']."',
        '".$_SESSION['cuenta_cliente']."',
        '".$documento_tipo."',
        '".$documento_numero."',
       '".$_SESSION['ventas']['linea']."',
       '".$_SESSION['contrato']."',
        '".$_SESSION['ventas']['documento_tipo']."',
       '".$_SESSION['ventas']['documento_numero']."',
        'generaciones por pulso',
        'sin datos',
        'sin datos',
        '0000-00-00 00:00:00',
        '1.00',
        '0',
        '".$cuota_descripcion."',
        '".$cuota_vencimiento."',
        '0000-00-00',
        'sin datos',
        'sin datos',
        'sin datos',
        'sin datos',
        'sin datos',
        'sin datos',
        '0.00',
        '0.00',
        '1.00',
        '0.00',
        '".$monto."',
        '0',
        '0.00',
        '".$hoy."',
        '".$hoy."',
        'no',
        '".ucwords($_SESSION['usuario_en_sesion'])."')";
        $save = $conexion->prepare($sql_insert);
        $save->execute();
        $mes_actual = $mes_actual +1;

    }
}


?>


<script type="text/javascript">

var tipo_de_calculo = '<?php echo $tipo_de_calculo; ?>';
var producto = '<?php echo  $datos_de_la_venta['producto'];?>';
var linea = '<?php echo $_POST['contrato_linea'];?>';
var centro =  '<?php echo $dato_documento[1];?>';

$('input[name*="<?php echo $campo_de_nacimiento; ?>"]').change(function(){

  if(tipo_de_calculo == "hoy")
  {
    var fecha_para_calculo = new Date();
  }
  else if(tipo_de_calculo == "defuncion")
  {
    var fecha_para_calculo = $('.defuncion').prop('value');
  }
  var nacimiento_fecha = $(this).prop('value');
  nacimiento_fecha = new Date(nacimiento_fecha);
  var edad = Math.floor((fecha_para_calculo-nacimiento_fecha) / (365.25 * 24 * 60 * 60 * 1000));
  var precio = 30000;
  var input_name = "";
  if(edad >= 0)
  {
      $(this).closest('tr').find('.edad').prop('value', edad);
      input_name = ($(this).closest('tr').find('.monto_cuota').attr("name"));
      console.log("######################################################");
      console.log("EDAD: "+edad+ " - LINEA: "+linea+" - CENTRO: "+centro+" - PRODUCTO: "+producto);
      console.log("######################################################");
      $.ajax({
              type: "POST",
              url: "../../funciones/buscar-precios.php",
              data: { 'edad':edad , 'linea':linea, 'centro':centro,'producto':producto},
              dataType: 'json',
              success: function(data){
                  precio = data.precio;
                  console.log(input_name);
                  var valor = "input[name='"+input_name+"']";
                  $(valor).val(precio);

              }
          });
  }
  else
  {
  	alert('El nacimiento elegido no es valido.');
  }
});

</script>






