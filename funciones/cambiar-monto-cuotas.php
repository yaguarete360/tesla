<?php 
error_reporting(0);
include 'conectar-base-de-datos-mysqli.php';
setlocale(LC_ALL,"es_ES@euro","es_ES","esp","es");
ini_set('max_execution_time', 0); 
$query2="SELECT *
FROM  $tabla_diario where documento_numero =".$docu_nro;
//echo $query2;die();
  //echo $query2;//impresion de la consulta en caso de error al momento de generar la consulta
  //recordset
					 $recordset2=mysqli_query($con,$query2) or die('No se pudo consultar estado 13');
					 while($rs2=mysqli_fetch_array($recordset2))
					  {
						$cuota = $rs2['cuota'];
						$cuota_vigencia = $rs2['cuota_vencimiento'];
						$documento_numero =$rs2['cuenta_documento_numero'];
						$derecho = $rs2['derecho'];
						$idx = $rs2['id'];
                        $preciox = 0;
						$query3="SELECT 
						`id`,
						`fecha`,
						`contrato`,
						`contrato_linea`,
						replace(replace(`contrato_linea`,'1','pse'),'2','mem') as sucursal,
						`contrato_centro`,
						`contrato_numero`,
						`estado`,
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
						TIMESTAMPDIFF(YEAR, beneficiario_nacimiento, '$cuota_vigencia') edad_cuota,
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
						`forma_de_pago`,
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
						`cobrador_nombre`,
						`datos_x`,
						`creado`,
						`modificado`,
						`borrado`,
						`usuario`,
						`origen`
					  FROM 
						$tabla_contrato where cuenta_documento_numero = $docu_nro;  ";
		//echo $query3;die();
  //echo $query3;//impresion de la consulta en caso de error al momento de generar la consulta
  //recordset
					$recordset3=mysqli_query($con,$query3) or die('No se pudo consultar estado 111');
					while($rs3=mysqli_fetch_array($recordset3))
					  {
						$nacimiento = $rs3['beneficiario_nacimiento'];
						$edad = $rs3['edad_cuota'];
						$producto = $rs3['producto'];

						$productox =  explode('-', $producto);

						$centro1 = $productox[0];
						$producto1 = $productox[1];
						$sucursalx = $rs3['sucursal'];
						
						//$rest = substr("abcdef", -2);
						$queryX1=" SELECT RIGHT(agrupador, 3) as centro,descripcion as plan,dato_1 as anho, 
						dato_2 as precio,
						dato_3,dato_4,left(dato_3,2) as desde,
						RIGHT(dato_3,2) as hasta,dato_4 as meses 
						FROM `agrupadores` 
						WHERE agrupador like '%precios en la web $sucursalx $centro1%' AND 
						dato_1 =YEAR(NOW()) AND 
						upper(RIGHT(agrupador, 3)) like upper('$centro1') AND
						descripcion like '$producto1' AND
						$edad between LEFT(dato_3,2) AND  
						RIGHT(dato_3,2) 
						ORDER BY `agrupadores`.`modificado` DESC";
						//echo $queryX1;die();

						if($recordsetX1=mysqli_query($con,$queryX1))
							{
								$rsX1=mysqli_fetch_array($recordsetX1);
							}

						   $precioz = str_replace(".","",$rsX1['precio']);
						   $preciox = $preciox + $precioz;
					  }

					  if ($derecho != $preciox){
						if($preciox!=0){
                            $queryX2="update $tabla_diario set derecho = $preciox  where id = $idx";
						}

						//echo $queryX2;die();
								if($recordsetX2=mysqli_query($con,$queryX2))
				
								{
									$rsX2=mysqli_fetch_array($recordsetX2);
								}
								//echo 'cuota modificado por variacion de edad en beneficiario';
					  }else{
						  echo 'esta cuota no sufrio cambios';
					  }


					  }



?>
