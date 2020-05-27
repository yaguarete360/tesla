<?php if (!isset($_SESSION)) {session_start();}

$esta_vista = basename(__FILE__);

$capitulo = $_GET['solapa'];
$titulo = $capitulo;
$subtitulo = "";

if(isset($_GET['categoria']))
{
	$categoria = $_GET['categoria'];
	$subtitulo = $_GET['categoria'];
}

include '../funciones/mostrar-cabecera.php';

echo '<div class="top-header"';
	echo 'style="background-image: url(../imagenes/iconos/cabecera.jpg)">';
	echo '<div class="container">';
		echo '<h1>'.$titulo.' '.$subtitulo.'</h1>';
	echo '</div>';
echo '</div>';
echo '<div class="container">';
	echo '<section class="interna">';

		$primera_letra = "";
		$ultima_letra = "";
		
		include "../funciones/conectar-base-de-datos.php";

		$permisos = array();
		$subsecciones = array();

		if($_SESSION['alias_en_sesion'] == "admin")
		{
			$menu_contenido = scandir('../vistas/'.$capitulo.'/',SCANDIR_SORT_ASCENDING);
			
			foreach ($menu_contenido as $posicion => $menu)
			{
				if($menu != "."
					and $menu != ".."
					and $menu != ".DS_Store"
					and $menu != "error_log"
					and !empty($menu)
				)
				{
					if($capitulo == "datos")
					{
						$tabla_actual = str_replace(".php", "", $menu);

						$subsecciones[$tabla_actual] = "si";
						$permisos['generales'][$tabla_actual] = "si";

					}
					else
					{
						$partes_del_menu = explode("-", $menu);
						if($partes_del_menu[0] == $categoria)
						{
							$subsecciones[str_replace(".php", "", $menu)] = "si";
						}
					}
				}
			}
		}
		else
		{
			$consulta = 'SELECT * 
			FROM permisos
			WHERE alias LIKE "'.$_SESSION['alias_en_sesion'].'"
			AND permiso LIKE "'.$capitulo.'%"
			AND borrado LIKE "no"
			ORDER BY permiso';

			$query = $conexion->prepare($consulta);
			$query->execute();

			while($rows = $query->fetch(PDO::FETCH_ASSOC))
			{
				if($capitulo == "datos")
				{
					$subsecciones_paso_1 = explode("-", $rows['permiso']);
					$quitador_de_asteriscos = explode("*", $subsecciones_paso_1[2]);
					if(isset($subsecciones_paso_1[2]))
					{
					    $permisos[$quitador_de_asteriscos[0]][$subsecciones_paso_1[1]] = "si";
					}
					else
					{
					    $permisos['generales'][$subsecciones_paso_1[1]] = "si";
					}
					$subsecciones[$subsecciones_paso_1[1]] = "si";
				}
				elseif($capitulo == "sintesis")
				{
				    $partes_del_permiso = explode("-", $rows['permiso']);
				    $subsecciones[$partes_del_permiso[1]] = "si";
				}
				else
				{
					$nombre_del_permiso = "";
					$partes_del_permiso = explode("-", $rows['permiso']);
					if($categoria == $partes_del_permiso[1]) $subsecciones[$partes_del_permiso[1]."-".$partes_del_permiso[2]] = "si";
				}
			}
		}

		$menus_posibles = scandir('../vistas/'.$capitulo.'/',SCANDIR_SORT_ASCENDING);

		foreach ($menus_posibles as $posicion => $menu)
		{
			if($menu != "."
					and $menu != ".."
					and $menu != ".DS_Store"
					and $menu != "error_log"
					and !empty($menu)
				) $menus_posibles_final[str_replace(".php", "", $menu)] = "si";
		}

		echo '<table>';
			foreach ($subsecciones as $subseccion => $validez)
			{
				if(isset($menus_posibles_final[$subseccion]))
				{
					if($capitulo == "datos")
					{
						$primera_letra = $subseccion[0];

						if($primera_letra != $ultima_letra)
						{
							echo '<tr>';
								echo '<td class="menu-item-letra">';
									echo '<b>'.strtoupper($primera_letra).'</b>';
								echo '</td>';
							echo '</tr>';
						}

						echo '<tr>';
							echo '<td class="menu-item">';
								echo ucwords(str_replace("_", " ", $subseccion));
							echo '</td>';

							echo '<td class="menu-icono">';
								if(isset($permisos['listados'][$subseccion]) or isset($permisos['generales'][$subseccion]))
								{
									echo '<a href="../funciones/formular-3-preparar-pdf.php?tabla_a_procesar='.$subseccion.'&capitulo=altas">';
										echo '<img src="../imagenes/iconos/boton-listados.png">';
									echo '</a>';
								}
							echo '</td>';

							echo '<td class="menu-icono">';
								if(isset($permisos['altas'][$subseccion]) or isset($permisos['generales'][$subseccion]))
								{
									echo '<a href="../funciones/formular-altas.php?tabla_a_procesar='.$subseccion.'&capitulo=altas">';
										echo '<img src="../imagenes/iconos/boton-altas.png">';
									echo '</a>';
								}
							echo '</td>';

							echo '<td class="menu-icono">';
								if(isset($permisos['consultas'][$subseccion]) or isset($permisos['generales'][$subseccion]))
								{
									echo '<a href="../funciones/formular-consultas.php?tabla_a_procesar='.$subseccion.'&capitulo=consultas">';
										echo '<img src="../imagenes/iconos/boton-consultas.png">';
									echo '</a>';
								}
							echo '</td>';

							echo '<td class="menu-icono">';
								if(isset($permisos['modificaciones'][$subseccion]) or isset($permisos['generales'][$subseccion]))
								{
									echo '<a href="../funciones/formular-modificaciones.php?tabla_a_procesar='.$subseccion.'&capitulo=modificaciones">';
										echo '<img src="../imagenes/iconos/boton-modificaciones.png">';
									echo '</a>';
								}
							echo '</td>';

							echo '<td class="menu-icono">';
								if(isset($permisos['bajas'][$subseccion]) or $_SESSION['alias_en_sesion'] == "admin")
								{
									echo '<a href="../funciones/formular-bajas.php?tabla_a_procesar='.$subseccion.'&capitulo=bajas">';
										echo '<img src="../imagenes/iconos/boton-bajas.png">';
									echo '</a>';
								}
							echo '</td>';
						echo '</tr>';
					}
					else
					{
						if($capitulo == "sintesis")
						{
						    
							$documentosInternos = scandir('../vistas/'.$capitulo.'/'.$subseccion, SCANDIR_SORT_DESCENDING);

							foreach($documentosInternos as $doc=>$documento)
							{
							    if($documento != '.' and 
							       $documento != '..' and 
							       $documento != '.DS_Store' and
							       $documento != '.htaccess' and
							       $documento != 'error_log' and
							       $subseccion == $_GET['categoria']
							    )
							    {
							    	echo '<tr>';
								    	echo '<td>';
											echo '<span class="menu-item">';
												echo '<a href="../vistas/'.$capitulo.'/'.$subseccion.'/'.$documento.'">';
													echo ucwords($documento);
												echo '</a>';
											echo '</span>';
								    	echo '</td>';
							    	echo '</tr>';
							    }
							}
						}
						else
						{
							$partes_de_la_subseccion = explode("-", $subseccion);
							$nombre_de_la_subseccion = $partes_de_la_subseccion[1];

							if(isset($menus_posibles_final[$subseccion]))
							{
								$primera_letra = $nombre_de_la_subseccion[0];

								if($primera_letra != $ultima_letra)
								{
									echo '<tr>';
										echo '<td class="menu-item-letra">';
											echo '<b>'.strtoupper($primera_letra).'</b>';
										echo '</td>';
									echo '</tr>';
								}
								
								$ruta = $subseccion;

								echo '<tr>';
									echo '<td class="menu-item">';
										echo ucwords(str_replace("_", " ", $nombre_de_la_subseccion));
									echo '</td>';
									
									echo '<td class="menu-icono">';
										echo '<a href="../vistas/'.$capitulo.'/'.$ruta.'.php">';
											echo '<img src="../imagenes/iconos/boton-altas.png">';
										echo '</a>';
									echo '</td>';
								echo '</tr>';
							}
						}
					}

					$ultima_letra = $primera_letra;
				}
			}

		echo '</table>';
	echo '</section>';
echo '</div>';

include '../funciones/mostrar-pie.php';

echo '</body>';
echo '</html>';

?>