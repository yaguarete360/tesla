<?php if (!isset($_SESSION)) {session_start();}

$esta_vista = basename(__FILE__);
$titulo_pagina = str_replace("-"," ",$esta_vista);
$titulo_pagina = str_replace(".php","",$titulo_pagina);
$titulo_pagina = explode(" ",$titulo_pagina);
$titulo_pagina = $titulo_pagina[0]." ".$titulo_pagina[1]." por ".$agrupador;
$_SESSION['titulo_pagina'] = $titulo_pagina;

$url = "../../";
include "../../funciones/mostrar-cabecera.php";

echo '<div class="top-header"';
	echo 'style="background-image: url(../../imagenes/iconos/cabecera.jpg)">';
	echo '<div class="container">';
		echo '<h1>'.$titulo_pagina.'</h1>';
	echo '</div>';
echo '</div>';
echo '<div class="container">';
	echo '<section class="interna">';
		echo '<div class="row">';
			echo '<div class="col-sm-12">';
				
				for($ind = 0; $ind < 7; $ind++) if(isset($_SESSION['legenda_grafico_'.$ind])) $_SESSION['legenda_grafico_'.$ind] = 0;
				for($ind = 0; $ind < 7; $ind++) if(isset($_SESSION['valor_grafico_'.$ind])) $_SESSION['valor_grafico_'.$ind] = 0;	
				
				$rango_dias = 1;				
				include "../../funciones/generar-persistencia.php";
				
				echo '<form class="rangos" method="post" action="">';
				
					include "../../funciones/seleccionar-fechas-rango.php";
				
					echo '<input type="submit" name="listar" value="Listar"/>';
				echo '</form>';
				$titulo = basename(__FILE__,'.php').': '.$subtitulo.' al '.$fecha_actual;
				
				include "../../funciones/conectar-base-de-datos.php";
				
				if(isset($_POST['listar']))
				{
					$contador_total = 'SELECT *,
					SUM('.$sumador.') AS sumador_total,
					COUNT('.$contador.') AS contador_total
					FROM '.$tabla_a_procesar.'
					WHERE borrado = "no"
					AND fecha
					BETWEEN "'.$_SESSION['fecha_desde'].'" 
					AND "'.$_SESSION['fecha_hasta'].'"';
                    //var_dump($contador_total);
					$query_totales = $conexion->prepare($contador_total);
					$query_totales->execute();	
					
					while($rows = $query_totales->fetch(PDO::FETCH_ASSOC))
					{
						$contador_total = $rows['contador_total'];
						$sumador_total = $rows['sumador_total'];
					}
					
					$consulta = 'SELECT *,
					SUM('.$sumador.') AS sumador_parcial,
					COUNT('.$contador.') AS contador_parcial
					FROM '.$tabla_a_procesar.'
					WHERE borrado = "no"
					AND fecha
					BETWEEN "'.$_SESSION['fecha_desde'].'" 
					AND "'.$_SESSION['fecha_hasta'].'" 
					GROUP BY '.$agrupador.'
					ORDER BY sumador_parcial
					DESC';
                    //var_dump($consulta);
					$query = $conexion->prepare($consulta);
					$query->execute();
					
					echo '<table class="autoancho">';					
						echo '<th><div class="encabezados">';
							echo ucfirst($agrupador);
						echo '</div></th>';
						echo '<th><div class="encabezados">';
							if($contador != $agrupador) echo $contador;
						echo '</div></th>';
						if(isset($calculo))
						{							
							echo '<th><div class="encabezados">';
								 echo $calculo;
							echo '</div></th>';
						}					
						echo '<th><div class="encabezados">';
							echo $sumador;
						echo '</div></th>';
						echo '<th><div class="encabezados">';
							echo ' % ';
						echo '</div></th>';
						
						$ind = 0;
						
						$porcentage_total = 0;
						
						while($rows = $query->fetch(PDO::FETCH_ASSOC))
						{
							echo '<tr>';
								echo '<td>';
									echo $rows[$agrupador];
									if(isset($calculo)) echo $calculo;
								echo '</td>';
								echo '<td class="derecha">';
									if($contador != $agrupador) echo number_format($rows['contador_parcial'],0,',','.');
								echo '</td>';
								if(isset($calculo))
								{							
									echo '<td class="derecha">';
										 echo $dato_calculo[$ind];
									echo '</td>';
								}
								echo '<td class="derecha">';
									echo number_format($rows['sumador_parcial'],0,',','.');	
								echo '</td>';
								echo '<td class="derecha">';
									$porcentage = $sumador_total > 0 ? $rows['sumador_parcial'] / $sumador_total * 100 : 0;
									echo number_format($porcentage,1,',','.');	
									$porcentage_total = $porcentage_total + $porcentage; 
								echo '</td>';
									$legenda[$ind] = $rows[$agrupador];
									$valor[$ind] = $rows['sumador_parcial'];
								echo '</td>';
							echo '</tr>';
							$ind ++;
						}
						echo '<tr>';
							echo '<td>';
								echo '<b>TOTAL</b>';	
							echo '</td>';
							echo '<td class="derecha">';
								if($contador != $agrupador) echo '<b>'.number_format($contador_total,0,',','.').'</b>';	
							echo '</td>';
							echo '<td class="derecha">';
								echo '<b>'.number_format($sumador_total,0,',','.').'</b>';	
							echo '</td>';
							echo '<td class="derecha" colspan="2">';
								echo '<b>'.number_format($porcentage_total,0,',','.').'</b>';	
							echo '</td>';
						echo '</tr>';			
					echo '</table>';			
				}
			echo '</div>';
		echo '</div>';
	echo '</section>';
echo '</div>';

include "../../funciones/mostrar-pie.php";

echo '</body>';
echo '</html>';

?>
