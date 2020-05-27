<?php if (!isset($_SESSION)) {session_start();}								
	
	$elemento = 0;
	foreach($_SESSION['campos'] as $campo_nombre => $campo_atributo)
	{
		$campo_a_procesar[$elemento] = $campo_nombre;
		$elemento++;
	} 

	echo '<center>';
	
		echo '<form method="post">';
			isset($_POST['dato_a_buscar']) ? $_SESSION['dato_a_buscar'] = $_POST['dato_a_buscar']: $valor_a_buscar = "";
			isset($_POST['dato_a_buscar']) ? $campo_a_buscar = $_POST['campo_a_buscar']: $campo_a_buscar = $campo_a_procesar[1];
			echo '<select name="campo_a_buscar"/>';	
				foreach ($campo_a_procesar as $campo)
				{
					($campo == $campo_a_buscar) ? $seleccionado = "selected" : $seleccionado = "";
					echo '<option value="'.$campo.'" '.$seleccionado.'>'.ucwords(str_replace("_", " ", $campo)).'</option>';
				}
			echo '</select>';
			if(!isset($_SESSION['dato_a_buscar'])) $_SESSION['dato_a_buscar'] = '';
	 		echo '<input type="text" name="dato_a_buscar" value="'.$_SESSION['dato_a_buscar'].'"size="45"/>';
			echo '<input type="submit" name="buscar" value="Buscar">';
			(isset($_POST['incluir_bajas']) and $_POST['incluir_bajas'] == "incluir_bajas") ? $chequeado = "checked" : $chequeado = "";
			echo '&nbsp&nbsp Ver Bajas <input type="checkbox" name="incluir_bajas" value="incluir_bajas" '.$chequeado.'>';
		echo '</form>';
		echo '<b>Responsable: '.$responsable.' - Contacto: '.$interno.'</b>';
	

		include "../funciones/conectar-base-de-datos.php";

		if(isset($_SESSION['dato_a_buscar']))
		{
		    $_SESSION['dato_a_buscar'] = str_replace("'",'"', $_SESSION['dato_a_buscar']);
			if(isset($_POST['incluir_bajas']) and $_POST['incluir_bajas'] == "incluir_bajas")
			{
			    
				$consulta = 'SELECT * 
				FROM '.$tabla_a_procesar.' 
				WHERE borrado = "no"
				AND '.$campo_a_buscar.' LIKE "%'.strtolower($_SESSION['dato_a_buscar']).'%"
				ORDER BY id
				DESC
				LIMIT 100';
			}
			else
			{
				$consulta = 'SELECT * 
				FROM '.$tabla_a_procesar.' 
				WHERE borrado = "no"
				AND '.$campo_a_buscar.' LIKE "%'.strtolower($_SESSION['dato_a_buscar']).'%"
				ORDER BY id
				DESC
				LIMIT 300';
			}
							
		}
		else
		{
			$consulta = 'SELECT * 
						 FROM '.$tabla_a_procesar.' 
						 WHERE borrado = "no"
						 ORDER BY id
						 DESC
						 LIMIT 100';

		}

		$query = $conexion->prepare($consulta);
		$query->execute();

		echo '<div style="overflow-x:scroll;">';
			echo '<table>';
	            echo '<tr>';
	    			foreach($_SESSION['campos'] as $campo_nombre => $campo_atributo)
	    			{
	    				if($campo_atributo['visible'] === "si")
	    				{									
	    					$campos_abreviados = explode("_",$campo_nombre);
	    
	    					if(isset($campos_abreviados[1]))
	    					{
	    						$encabezado = "";
	    						foreach($campos_abreviados as $campo_abreviado) $encabezado.= ucwords(substr($campo_abreviado,0,3)).".";
	    					}
	    					else
	    					{
	    						$encabezado = ucwords($campo_nombre);
	    					}
	    
	    					if($campo_atributo['formato'] != "oculto") echo '<td class="encabezados">'.$encabezado.'</td>';
	    				}
	    			}
	    
	    			echo '<td class="encabezados">';
	    				echo "";
	    			echo '</td>';
	            echo '</tr>';
	            
				while($rows = $query->fetch(PDO::FETCH_ASSOC))
				{				

					echo '<tr>';	
						foreach($_SESSION['campos'] as $campo_nombre => $campo_atributo)
						{									
							if($campo_atributo['visible'] == "si")
							{
							    if(isset($sumar_campos[$campo_nombre]) and !isset($sumas[$campo_nombre])) $sumas[$campo_nombre] = 0;
							    
								if($campo_atributo['formato'] == "texto" or 
								   $campo_atributo['formato'] == "fecha" or 
								   $campo_atributo['formato'] == "asistido" or
								   $campo_atributo['formato'] == "hora" or
								   $campo_atributo['formato'] == "vista")
								{
									echo '<td class="izquierda">';
										echo ucwords(substr($rows[$campo_nombre],0,35));							
								} 

								if($campo_atributo['formato'] == "numero-texto" or
								   $campo_atributo['formato'] == "asistido-derecha" )
								{
									echo '<td class="derecha">';
										echo $rows[$campo_nombre];
								}									

								if($campo_atributo['formato'] == "numero")
								{								
									echo '<td class="derecha">';
										echo number_format($rows[$campo_nombre],$campo_atributo['decimales'],",",".");
								}
								if(isset($sumar_campos[$campo_nombre])) $sumas[$campo_nombre] = $sumas[$campo_nombre] + $rows[$campo_nombre];
							}
						}
						
						if($listado_tipo != "altas")
						{
							echo '<td class="menu-icono">';
								echo '<a href="../funciones/formular-'.$listado_tipo.'.php?tabla_a_procesar='.$tabla_a_procesar.'&id='.$rows['id'].'">';
									echo '<img src="../imagenes/iconos/boton-'.$listado_tipo.'.png">';
								echo '</a>';								
							echo '</td>';
						}

					echo '</tr>';
				}
				
				echo '<tr>';
					foreach($_SESSION['campos'] as $campo_nombre => $campo_atributo) 
					{
					    if($campo_atributo['visible'] == "si") 
					    {
				            echo '<td style="text-align:right;">';
					            if(isset($sumar_campos[$campo_nombre]) and $sumas[$campo_nombre] != 0) echo '<b>'.number_format($sumas[$campo_nombre]).'</b>';
					        echo '</td>';
					    }
					}
				echo '</tr>';
			echo '</table>';
		echo '</div>';
	echo '</center>';
?>
