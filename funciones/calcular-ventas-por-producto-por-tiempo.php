<?php if (!isset($_SESSION)) {session_start();}

$url = "../../../";

$esta_pagina = basename(__FILE__);
$titulo_pagina = str_replace("-"," ",$esta_pagina);
$titulo_pagina = str_replace(".php","",$titulo_pagina);

include $url.'pse-red/funciones/mostrar-cabecera.php';

echo '<div class="top-header"';
	echo 'style="background-image: url('.$url.'pse-red/imagenes/iconos/cabecera.jpg)">';
	echo '<div class="container">';
		echo '<h1>'.$titulo_pagina.'</h1>';
	echo '</div>';
echo '</div>';
echo '<div class="container">';
	echo '<section class="interna">';
		echo '<div class="row">';
			echo '<div class="col-sm-12">';
				$_SESSION['responsable'] = "";
				
				if(isset($tabla_a_procesar)) include $url.'pse-red/archivos/datos/'.$tabla_a_procesar.'.php';
				
				if(isset($responsable) and !empty($responsable)) $_SESSION['responsable'] = $responsable;
				echo '<div>';
					echo "<b>Responsable de Tabla Base: </b>".$responsable;
				echo '</div>';
				$ano_anterior_0 = date('Y');
				$ano_anterior_1 = $ano_anterior_0 -1;
				$ano_anterior_2 = $ano_anterior_0 -2;
				$ano_anterior_3 = $ano_anterior_0 -3;
				$ano_anterior_4 = $ano_anterior_0 -4;
				$ano_anterior_5 = $ano_anterior_0 -5;
				$ano_anterior_6 = $ano_anterior_0 -6;
				$ano_anterior_7 = $ano_anterior_0 -7;
				$ano_anterior_8 = $ano_anterior_0 -8;
				$ano_anterior_9 = $ano_anterior_0 -9;
				$ano_anterior_10 = $ano_anterior_0 -10;
				$rangodias = 1;
				
				include $url.'pse-red/funciones/generar-persistencia.php';
				
				echo '<form class="rangos" method="post" action="">';
					echo '<select name="ano_a_usar" class="datos">';
						echo '<option value="'.$ano_anterior_0.'"selected>'.$ano_anterior_0.'</option>';
						echo '<option value="'.$ano_anterior_1.'">'.$ano_anterior_1.'</option>';
						echo '<option value="'.$ano_anterior_2.'">'.$ano_anterior_2.'</option>';
						echo '<option value="'.$ano_anterior_3.'">'.$ano_anterior_3.'</option>';
						echo '<option value="'.$ano_anterior_4.'">'.$ano_anterior_4.'</option>';
						echo '<option value="'.$ano_anterior_5.'">'.$ano_anterior_5.'</option>';
						echo '<option value="'.$ano_anterior_6.'">'.$ano_anterior_6.'</option>';
					echo '</select>';
					echo '<br/>';
					echo '<input type="submit" name="graficar" class="datos" value="Graficar"/>';
				echo '</form>';
				$titulo = basename(__FILE__,'.php').': '.$subtitulo.' al '.$fechaActual;
				
				include $url.'pse-red/funciones/conectar-base-de-datos.php';
				
				$ano_a_usar = isset($_POST['ano_a_usar']) ? $_POST['ano_a_usar'] : date("Y");

				$meses = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
				if(isset($_POST['graficar']))
				{
					echo '<table class="autoancho">';
					    echo '<tr>';
					        echo '<td colspan="5">';
					            echo "El siguiente grafico va a obtener los datos para el año ".$ano_a_usar;
					        echo '</td>';
					    echo '</tr>';
					echo '</table>';
					$n = 0;
					$productos = array();
					
					$consulta_producto = 'SELECT *, SUM('.$sumador.') AS ordenador
					FROM '.$tabla_a_procesar.' 
					WHERE borrado = "0000-00-00"
					AND fecha LIKE "'.$ano_a_usar.'%"
					GROUP BY producto
					ORDER BY ordenador
					DESC';

					$query_producto = $conexion->prepare($consulta_producto);
					$query_producto->execute();
					
					while($rowsProducto = $query_producto->fetch(PDO::FETCH_ASSOC))
					{
						$_SESSION['productos'][$n] = $rowsProducto['producto'];
						$n++;
					}
					$ind = 0;
					foreach ($_SESSION['productos'] as $producto)
					{
						foreach ($meses as $mes)
						{
							$producto_mes = array();
							$ano_mes = array();
							$ano_mes = $ano_a_usar.'-'.$mes;
							$consulta_mes = 'SELECT
							SUM('.$sumador.') AS suma_mes
							FROM '.$tabla_a_procesar.'
							WHERE borrado = "0000-00-00" 
							AND fecha LIKE "'.$ano_mes.'%"
							AND producto LIKE "'.$producto.'"
							ORDER BY suma_mes
							DESC';

							$query_suma_mes = $conexion->prepare($consulta_mes);
							$query_suma_mes->execute();
							
							while($rows_suma_mes = $query_suma_mes->fetch(PDO::FETCH_ASSOC))
							{
								$_SESSION['resultado_mes'][$ind] = $rows_suma_mes['suma_mes'];								
								
								if(empty($_SESSION['resultado_mes'][$ind]) or $_SESSION['resultado_mes'][$ind] === "0")
								{
									$_SESSION['resultado_mes'][$ind] = "0";
								}								
								$_SESSION['producto_mes'][$ind] = $producto.' = '.$_SESSION['resultado_mes'][$ind];
								
								$ind++;
							
							}
						}
					}
					echo '<td>';
						echo '<form method="post" action="'.$url.'pse-red/funciones/graficar-columnas-comparativas-stacked.php">';
							echo '<input type="submit" name="graficar_3" value="Columnas Comparativas por producto"/>';
						echo '</form>';
					echo '</td>';
				}
			echo '</div>';
		echo '</div>';
	echo '</section>';
echo '</div>';

include $url.'pse-red/funciones/mostrar-pie.php';

echo '</body>';
echo '</html>';

?>
