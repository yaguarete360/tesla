<?php if (!isset($_SESSION)) {session_start();}

$esta_vista = basename(__FILE__,'.php');

$titulo = str_replace("-", " ", $esta_vista);
$url = "../";
include "../funciones/mostrar-cabecera.php";

echo '<div class="top-header"';
	echo 'style="background-image: url(../imagenes/iconos/cabecera.jpg)">';
	echo '<div class="container">';
		echo '<h1>'.$titulo.'</h1>';
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
			
			echo '<h4>';
				echo 'Contenidos encontrados:';
			echo '</h4>';
			
			$carpetas_a_buscar['quienes-somos'] = 'publica';
			$carpetas_a_buscar['sepelios'] = 'publica';
			$carpetas_a_buscar['servicios'] = 'publica';
			$carpetas_a_buscar['sucursales'] = 'publica';
			$carpetas_a_buscar['datos'] = "privada";
			$carpetas_a_buscar['procesos'] = "privada";
			$carpetas_a_buscar['reportes'] = "privada";
			$carpetas_a_buscar['sintesis'] = "privada";

			$carpetas = scandir("../vistas/", SCANDIR_SORT_ASCENDING);

			$existen_archivos = "no";
        
        	if(isset($_SESSION['usuario_en_sesion']))
        	{
	        	$consulta = ' SELECT *
				FROM permisos
				WHERE borrado LIKE "no"
				AND alias LIKE "'.$_SESSION['alias_en_sesion'].'"';
				$query = $conexion->prepare($consulta);
				$query->execute();
				while($rows = $query->fetch(PDO::FETCH_ASSOC)) $permisos_nombres[$rows['permiso']] = "";
        	}

			foreach($carpetas as $car=> $carpeta)
			{  
			    if(isset($carpetas_a_buscar[$carpeta]))
			    {
			        $vistas = scandir($url.'vistas/'.$carpeta, SCANDIR_SORT_ASCENDING);
			        foreach($vistas as $vis=> $vista)
			        {
			        	if($vista[0] != ".")
			        	{
					    	$archivo_a_revisar = strtolower($carpeta."-".str_replace(".php", "", $vista));
					    	if($carpetas_a_buscar[$carpeta] == "publica" or ($carpetas_a_buscar[$carpeta] == "privada" and isset($_SESSION['usuario_en_sesion']) and (isset($permisos_nombres[$archivo_a_revisar]) or $_SESSION['alias_en_sesion'] == "admin")))
					    	{
					        	foreach ($a_buscar as $pos => $valor_a_buscar)
					        	{
					                if(strpos(strtolower($vista), strtolower($valor_a_buscar)) !== false or strpos(strtolower($carpeta), strtolower($valor_a_buscar)) !== false)
					                {
				                		$nombre = str_replace(".php","",ucfirst(str_replace("-"," ",$carpeta)).', '.str_replace("-"," ",$vista));
					                	if($carpeta == "datos")
					                	{
						                	$href = 'funciones/mostrar-menu-contenido.php?solapa=datos';
					                	}
					                	else
					                	{
						                	$href = 'vistas/'.$carpeta.'/'.$vista;
					                	}
										echo '<a href="'.$url.'/'.$href.'"><b>'.$nombre.'</b></a>';
										echo '<br/>';
										$existen_archivos = "si";
					                }
					        	}
			    			}
			        	}
			        }
			    }
			}
 
 
 //and (isset($permisos_nombres[$archivo_a_revisar]) or $_SESSION['alias_en_sesion'] == "admin")
 
 
			if($existen_archivos == "no") echo 'No se encontró contenido alguno al respecto.';
			
			include "../funciones/conectar-base-de-datos.php";
			
			$consulta = ' SELECT fecha,difunto, tipo, defuncion_fecha, 
			nacimiento, inicio_fecha, inicio_hora, capilla
			FROM difuntos
			WHERE borrado LIKE "no"
			AND difunto NOT LIKE "%sin datos%"
			AND difunto NOT LIKE "%s/d%"
			AND difunto NOT LIKE "%no aplicable%"
			AND difunto NOT LIKE "%n/a%" ';
			foreach ($a_buscar as $pos => $valor_a_buscar) if(!empty($valor_a_buscar)) $consulta.= 'AND difunto LIKE "%'.$valor_a_buscar.'%" ';
			$consulta.= 'LIMIT 500';

			$query = $conexion->prepare($consulta);
			$query->execute();
			
			while($rows = $query->fetch(PDO::FETCH_ASSOC))
			{
			    $resultados[$ind]['url_destino'] = "../vistas/sepelios/consultar-exequias.php";
			    $resultados[$ind]['titulo'] = $rows['difunto'];
			    switch ($rows['tipo']) 
			    {
			    	case 'Cremacion':
			    		$prestacion[$ind]['prestacion'] = "cremaciones";
			    	break;
			    	case 'Exhumacion':
			    		$prestacion[$ind]['prestacion'] = "exhumaciones";
			    	break;
			    	case 'Exunilaterales':
			    		$prestacion[$ind]['prestacion'] = "exhumaciones";
			    	break;
			    	case 'Inhumacion':
			    		$prestacion[$ind]['prestacion'] = "inhumaciones";
			    	break;
			    	case 'Sepelio':
			    		$prestacion[$ind]['prestacion'] = "sepelios";
			    	break;
			    	case 'Traslado':
			    		$prestacion[$ind]['prestacion'] = "traslados";
			    	break;
			    	
			    	default:
			    		$prestacion[$ind]['prestacion'] = "sepelios";
			    	break;
			    }
			    $resultados[$ind]['descripcion'] = $rows['tipo'].' del día '.$rows['inicio_fecha'];
			    $ind++;
			}

			if(isset($resultados) and count($resultados) > 0)
			{
				echo '<h4>Prestaciones encontradas:</h4>';
				foreach($resultados as $res=>$resultado) 
				{
					echo '<div class="col-sm-12">';
						if(isset($resultados[$res]['url_destino']))
						{
							echo '<a href="'.$url.'vistas/'.$resultados[$res]['url_destino'].'?listar='.$prestacion[$res]['prestacion'].'&difunto='.$resultados[$res]['titulo'].'"><b>'.strtoupper(str_replace("-"," ",$resultados[$res]['titulo'])).'</b></a>';
							echo '<br/>';
							echo $resultados[$res]['descripcion'];
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

include $url.'funciones/mostrar-pie.php';

echo '</body>';
echo '</html>';

?>
