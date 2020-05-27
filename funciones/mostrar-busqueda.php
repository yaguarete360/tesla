<?php if (!isset($_SESSION)) {session_start();}

$url = "../../";

$retroceder= "";
$buscar = "si";
$esta_vista = basename(__FILE__,'.php');

include $url.'pse-red/funciones/mostrar-cabecera.php';

include $url.'pse-red/funciones/conectar-base-de-datos.php';

echo '<div class="top-header"';
	echo 'style="background-image: url('.$url.'pse-red/imagenes/iconos/cabecera.jpg)">';
	echo '<div class="container">';
		echo '<h1>BUSCAR CONTENIDOS</h1>';
	echo '</div>';
echo '</div>';
echo '<div class="container">';
	echo '<section class="interna">';
		echo '<div class="row">';
			echo '<div class="col-sm-12">';
			if(isset($_GET['q']))
			{
				echo '<b>';
					echo 'Buscando...'.$_GET['q'];
				echo '</b>';
			}

			echo '</div>';
		echo '</div>';
		echo '<div class="row">';
			$ind = 0;
			$a_buscar = explode(" ",$_GET['q']);
			$a_buscar_1 = isset($a_buscar[0]) ? $a_buscar[0] : "";
			$a_buscar_2 = isset($a_buscar[1]) ? $a_buscar[1] : "";
			$a_buscar_3 = isset($a_buscar[2]) ? $a_buscar[2] : "";
			$a_buscar_4 = isset($a_buscar[3]) ? $a_buscar[3] : "";
			echo '<h4>';
				echo 'Contenidos encontrados:';
			echo '</h4>';

			$i_alias=0;
			$permisosDelAlias = array();
			$consulta_alias = ' SELECT *
			              FROM auxiliares_permisos
			              WHERE borrado = "0000-00-00"
			              AND alias = "'.$_SESSION['alias_en_sesion'].'"
			              ';
			$query_alias = $conexion->prepare($consulta_alias);
			$query_alias->execute();
			while($rows = $query_alias->fetch(PDO::FETCH_ASSOC))
			{
				$permisoExplo = explode("-", $rows['permiso']);
				foreach ($permisoExplo as $pEx => $preExplo)
				{
					if($pEx == "0")
					{
						$permisosDelAlias[$i_alias] = ucwords($preExplo);
					}
					elseif($pEx == "1")
					{
						$permisosDelAlias[$i_alias].= ", ".$preExplo;
					}
					else
					{
						$permisosDelAlias[$i_alias].= " ".$preExplo;
					}
				}
				$i_alias++;
			}

			$carpetas = scandir($url.'pse-red/vistas/',SCANDIR_SORT_ASCENDING);
			
			foreach($carpetas as $carpeta_nombre => $carpeta)
			{  
			    if( $carpeta != '.' and 
			        $carpeta != '..' and 
			        $carpeta != '.DS_Store' and
			        $carpeta != 'error_log'
			    )
			    {  
			        $vistas = scandir($url.'pse-red/vistas/'.$carpeta,SCANDIR_SORT_ASCENDING); 
			        foreach($vistas as $vis=> $vista)
			        {
			            if( $vista != '.' and 
			                $vista != '..' and 
			                $vista != '.DS_Store' and
			                $vista != 'error_log'
			            )
			            { 
			                $contenido = str_replace(".php","",ucfirst(str_replace("-"," ",$carpeta)).', '.str_replace("-"," ",$vista)); 			                			                
			                if (strpos(strtolower($contenido), strtolower($a_buscar_1)) !== false)
			                {
			                	if(in_array($contenido, $permisosDelAlias) or $_SESSION['alias_en_sesion'] == "admin")
			                	{
				                    echo '<a href="'.$url.'pse-red/vistas/'.$carpeta.'/'.$vista.'"><span style="font-weight:bold">'.ucwords($contenido).'</span></a>';
				                    echo '<br/>';
			                	}
			                	else
			                	{
			                		echo '<span style="color:#bfbfbf">'.ucwords($contenido).'</span>';
				                    echo '<br/>';
			                	}
			                    $si_hay = "";
			                }
			            }
			        }
			    }
			}

			if(!isset($si_hay)) echo 'No se encontró contenido alguno al respecto.';
			
			$consulta = ' SELECT fecha, difunto, tipo, defuncion_fecha, nacimiento, inicio_fecha, inicio_hora, capilla
			FROM operaciones_difuntos
			WHERE borrado = "0000-00-00"
			AND difunto LIKE "%'.$a_buscar_1.'%"
			AND difunto LIKE "%'.$a_buscar_2.'%"
			AND difunto LIKE "%'.$a_buscar_3.'%"
			AND difunto LIKE "%'.$a_buscar_4.'%"
			LIMIT 500';

			$query = $conexion->prepare($consulta);
			$query->execute();
			
			while($rows = $query->fetch(PDO::FETCH_ASSOC))
			{
			    $resultados[$ind]['url_destino'] = $url.'pse-red/vistas/sepelios/consultar-exequias.php';
			    $resultados[$ind]['titulo'] = '<b>'.$rows['difunto'].'</b>';
			    $prestacion = "";
			    $resultados[$ind]['descripcion'] = $rows['tipo'].' del día '.$rows['inicio_fecha'];
			    $ind++;
			}
			
			if(isset($resultados) and count($resultados) > 0)
			{
				echo '<h4>Prestaciones encontradas:</h4>';
				foreach($resultados as $resultado_nombre => $resultado) 
				{
					echo '<div class="col-sm-12">';
						if(isset($resultados[$resultado_nombre]['url_destino']))
						{
							echo strtoupper(str_replace("-"," ",$resultados[$resultado_nombre]['titulo']));
							echo '<br/>';
							echo $resultados[$resultado_nombre]['descripcion'];
							echo '<br/>';
						}
					echo '</div>';
				}
			}
			else
			{
				echo '<div class="col-sm-12">';
					echo '<p><em>No hay datos de sepelios con dicho nombre.</em></p>';
				echo '</div>';
			}

		echo '</div>';
	echo '</section>';
echo '</div>';

include $url.'pse-red/funciones/mostrar-pie.php';

echo '</body>';
echo '</html>';

?>
