<?php if (!isset($_SESSION)) {session_start();}				

	$_SESSION['tabla']	= isset($_POST['tabla']) ? str_replace(".php","",$_POST['tabla']) : $_SESSION['tabla'];
	$_SESSION['campo']	= isset($_POST['campo']) ? $_POST['campo'] : $_SESSION['campo'];
	$_SESSION['desde']	= isset($_POST['desde']) ? $_POST['desde'] : $_SESSION['desde'];
	$_SESSION['hasta']	= isset($_POST['hasta']) ? $_POST['hasta'] : $_SESSION['hasta'];

	if(!isset($_POST['tabla']) and !isset($_POST['campo']))
	{

		echo '<form method="post">';
			
			echo '<label class="etiqueta-input">Tabla: </label>';
			echo '<select name="tabla">';
				
				$archivos = scandir('../../vistas/datos/');

				if(isset($_POST['tabla']) or isset($_SESSION['tabla']))
				{
					echo $_SESSION['tabla'];
					echo '<br/>';

					echo '<option value="'.$_SESSION['tabla'].'" selected>'.$_SESSION['tabla'].'</option>';

					foreach ($archivos as $archivo_vuelta => $archivo_nombre)
					{
						if($archivo_nombre != "." and 
					    $archivo_nombre != ".." and 
					    $archivo_nombre != ".DS_Store" and
					    $archivo_nombre != "error_log" and					    
					    !strpos($archivo_nombre, 'pdf'))
					    {									
						    echo '<option>'.$archivo_nombre.'</option>'.$archivo_nombre;
					    }
					}				
				}
				else
				{
					foreach ($archivos as $archivo_vuelta => $archivo_nombre)
					{
						if($archivo_nombre != "." and 
					    $archivo_nombre != ".." and 
					    $archivo_nombre != ".DS_Store" and
					    $archivo_nombre != "error_log" and					    
					    !strpos($archivo_nombre, 'pdf'))
					    {									
						    echo '<option>'.$archivo_nombre.'</option>'.$archivo_nombre;
					    }
					}									
				}						
			echo '</select>';

			echo '<input type="submit" name="elegir_tabla" value="Elegir tabla"/>';
		echo '</form>';
	}
	else
	{
		echo '<label class="etiqueta-input">Tabla: </label>';
		echo $_SESSION['tabla'];
		echo '<br/>';
	}

	if(isset($_POST['elegir_tabla']))
	{									
		include '../datos/'.$_SESSION['tabla'].'.php';
		
		echo '<form method="post">';
			
			echo '<label class="etiqueta-input">Campo: </label>';
			echo '<select name="campo">';
				if(isset($_POST['elegir_tabla']))
				{							
					if(isset($_POST['campo']) or isset($_SESSION['campo']))
					{
						echo '<option value="'.$_SESSION['campo'].'" selected>'.$_SESSION['campo'].'</option>';
						foreach ($_SESSION['campos'] as $campo_nombre => $campo_atributo)
						{
							if($campo_nombre != "." and 
						    $campo_nombre != ".." and 
						    $campo_nombre != ".DS_Store" and
						    $campo_nombre != "error_log" and					    
						    !strpos($campo_nombre, 'pdf'))
						    {									
							    echo '<option>'.$campo_nombre.'</option>'.$campo_nombre;
						    }
						}
					}
					else
					{
						foreach ($_SESSION['campos'] as $campo_nombre => $campo_atributo)
						{
							if($campo_nombre != "." and 
						    $campo_nombre != ".." and 
						    $campo_nombre != ".DS_Store" and
						    $campo_nombre != "error_log" and					    
						    !strpos($campo_nombre, 'pdf'))
						    {									
							    echo '<option>'.$campo_nombre.'</option>'.$campo_nombre;
						    }
						}
					}						
					
				}						
			echo '</select>';
			echo '<br/>';

			echo '<label class="etiqueta-input">Desde el id: </label>';
			echo '<input type="text" name="desde" value="'.$_SESSION['desde'].'"/>';
			echo '<br/>';

			echo '<label class="etiqueta-input">Hasta el id: </label>';
			echo '<input type="text" name="hasta" value="'.$_SESSION['hasta'].'"/>';
			echo '<br/>';


			echo '<input type="hidden" name="tabla" value="'.$_SESSION['tabla'].'"/>';
			echo '<input type="submit" name="elegir_campo" value="Elegir campo"/>';
		echo '</form>';	
	}
	else
	{
		if(isset($_POST['tabla']))
		{
			echo '<label class="etiqueta-input">Campo: </label>';
			echo $_SESSION['campo'];
			echo '<br/>';			
			echo '<label class="etiqueta-input">Desde: </label>';
			echo $_SESSION['desde'];
			echo '<br/>';			
			echo '<label class="etiqueta-input">Hasta: </label>';
			echo $_SESSION['hasta'];
			echo '<br/>';			
		}

	}

?>
