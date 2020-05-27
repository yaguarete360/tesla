<?php if (!isset($_SESSION)) {session_start();}

$esta_vista = basename(__FILE__);
$titulo_pagina = str_replace("-"," ",$esta_vista);
$titulo_pagina = str_replace(".php","",$titulo_pagina);
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
				for($ind = 0; $ind < 7; $ind++) if(isset($_SESSION['legen_grafico_'.$ind])) $_SESSION['legen_grafico_'.$ind] = 0;
				for($ind = 0; $ind < 7; $ind++) if(isset($_SESSION['valor_grafico_'.$ind])) $_SESSION['valor_grafico_'.$ind] = 0;	
				
				$rango_dias = 1;				
				include "../../funciones/generar-persistencia.php";
				
				echo '<form class="rangos" method="post" action="">';
									
					include "../../funciones/seleccionar-fechas-rango.php";
				
					echo '<input type="submit" name="listar" value="Listar"/>';
				echo '</form>';
				
				include "../../funciones/conectar-base-de-datos.php";
				
				if(isset($_POST['listar']))
				{
					$casos_total = 'SELECT *,
					SUM(abc) AS exequias_abc,
					SUM(uh) AS exequias_uh,
					COUNT('.$entidades.') AS casos_total
					FROM exequias
					WHERE borrado = "no"
					AND fecha
					BETWEEN "'.$_SESSION['fecha_desde'].'" 
					AND "'.$_SESSION['fecha_hasta'].'"';
                    //var_dump($casos_total);
					$queryTotal = $conexion->prepare($casos_total);
					$queryTotal->execute();	
					
					while($rows = $queryTotal->fetch(PDO::FETCH_ASSOC))
					{
						$casos_total = $rows['casos_total'];
						$exequias_total_abc = $rows['exequias_abc'];
						$exequias_total_uh = $rows['exequias_uh'];
					}
					$exequias_totales = $exequias_total_abc + $exequias_total_uh;
					
					$consulta = 'SELECT *,
					SUM(abc) AS exequias_abc,
					SUM(uh) AS exequias_uh,
					SUM(uh + abc) AS exequias,
					COUNT('.$entidades.') AS casos
					FROM exequias
					WHERE borrado = "no"
					AND fecha
					BETWEEN "'.$_SESSION['fecha_desde'].'" 
					AND "'.$_SESSION['fecha_hasta'].'" 
					GROUP BY '.$entidades.'
					ORDER BY '.$medidores.'
					DESC';
                    //var_dump($consulta);
					$query = $conexion->prepare($consulta);
					$query->execute();
					
					$_SESSION['tortaTitulo'] = 'Participacion por '.strtoupper($agrupador).' desde el '.
					$_SESSION['fecha_desde'].' hasta el '.$_SESSION['fecha_hasta'].'.';
					if(isset($casos_total) and $casos_total > 0)
					{
						echo '<table class="autoancho">';					
							echo '<th>';
								echo ucfirst($entidades)."&nbsp&nbsp&nbsp";
							echo '</th>';
							echo '<th align="right">';
								echo "&nbsp&nbsp&nbsp".ucfirst($servicios);
							echo '</th>';
							echo '<th align="right">';
								echo "&nbsp&nbsp&nbsp&nbsp&nbsp".'UH';
							echo '</th>';
							echo '<th align="right">';
								echo "&nbsp&nbsp&nbsp&nbsp&nbsp".'ExS UH';
							echo '</th>';
							echo '<th align="right">';
								echo "&nbsp&nbsp&nbsp&nbsp&nbsp".'ABC';
							echo '</th>';
							echo '<th align="right">';
								echo "&nbsp&nbsp&nbsp&nbsp&nbsp".'ExS ABC';
							echo '</th>';
							echo '<th align="right">';
								echo "&nbsp&nbsp&nbsp&nbsp&nbsp".'Ambos';
							echo '</th>';
							echo '<th align="right">';
								echo "&nbsp&nbsp&nbsp&nbsp&nbsp".'ExS Ambos';
							echo '</th>';
							echo '<th align="right">';
								echo "&nbsp&nbsp&nbsp&nbsp&nbsp".' % ENT.';
							echo '</th>';
							echo '<th align="right">';
								echo "&nbsp&nbsp&nbsp&nbsp&nbsp".' % EXE.';
							echo '</th>';							
							$ind = 0;
							while($rows = $query->fetch(PDO::FETCH_ASSOC))
							{
							  //echo 'CASOS TOTAL: '.$casos_total;    
								echo '<tr>';
									echo '<td>';
									echo $rows[$entidades];	
									echo '&nbsp&nbsp</td>';
									echo '<td class="derecha">';
										echo number_format($rows['casos'],0,',','.');	
									echo '&nbsp&nbsp</td>';
									echo '<td class="derecha">';
										echo number_format($rows['exequias_uh'],0,',','.');	
									echo '&nbsp&nbsp</td>';
									echo '<td class="derecha">';
										echo number_format($rows['exequias_uh'] / $rows['casos'],1,',','.');	
									echo '&nbsp&nbsp</td>';
									echo '<td class="derecha">';
										echo number_format($rows['exequias_abc'],0,',','.');
									echo '&nbsp&nbsp</td>';
									echo '<td class="derecha">';
										echo number_format($rows['exequias_abc'] / $rows['casos'],1,',','.');
									echo '&nbsp&nbsp</td>';
									echo '<td class="derecha">';
										echo number_format($rows['exequias_uh'] + $rows['exequias_abc'],0,',','.');
									echo '&nbsp&nbsp</td>';
									echo '<td class="derecha">';
										echo number_format(($rows['exequias_uh'] + $rows['exequias_abc']) / $rows['casos'],1,',','.');
									echo '&nbsp&nbsp</td>';
									echo '<td class="derecha">';
										echo number_format(($rows['casos'] / $casos_total * 100),2,',','.').' %';	
									echo '&nbsp&nbsp</td>';
									echo '<td class="derecha">';
										echo number_format((($rows['exequias_uh'] + $rows['exequias_abc']) / $exequias_totales * 100),2,',','.').' %';	
									echo '&nbsp&nbsp</td>';
										switch ($agrupador) 
										{
											case 'casos':
												$legenda[$ind] = $rows[$entidades];
												$valor[$ind] = $rows['casos'];
												$total_otros = $casos_total;
											break;

											case 'exequias':
												$legenda[$ind] = $rows[$entidades];
												$valor[$ind] = $rows['exequias_uh'] + $rows['exequias_abc'];
												$total_otros = $exequias_totales;
											break;
										}
									echo '</td>';
								echo '</tr>';
								$ind ++;
							}
							echo '<tr>';
								echo '<td>';
									echo '<b>TOTAL</b>';	
								echo '&nbsp&nbsp</td>';
								echo '<td class="derecha">';
									echo '<b>'.number_format($casos_total,0,',','.').'</b>';	
								echo '&nbsp&nbsp</td>';
								echo '<td class="derecha" colspan="2">';
									echo '<b>'.number_format($exequias_total_uh / $casos_total,1,',','.').'</b>';	
								echo '&nbsp&nbsp</td>';
								echo '<td class="derecha" colspan="2">';
									echo '<b>'.number_format($exequias_total_abc / $casos_total,1,',','.').'</b>';	
								echo '&nbsp&nbsp</td>';
								echo '<td class="derecha">';
									echo '<b>'.number_format($exequias_totales,0,',','.').'</b>';	
								echo '&nbsp&nbsp</td>';
								echo '<td class="derecha">';
									echo '<b>'.number_format($exequias_totales / $casos_total,1,',','.').'</b>';	
								echo '&nbsp&nbsp</td>';
							echo '</tr>';
						echo '<tr>';			
					}
					else
					{
						echo '<center>';					
							echo '<h2>'.$_SESSION['titulo_pagina'].'</h2>';
							echo $_SESSION['tortaTitulo'].'<br/>';
							echo '<h5>No hay datos en este periodo</h5>';
						echo '</center>';
					}
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
