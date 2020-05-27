<?php if (!isset($_SESSION)) {session_start();}
ini_set('max_execution_time', 1200);
ini_set("memory_limit","5G");

$esta_pagina = basename(__FILE__);
$esta_vista = $esta_pagina;
$capitulo = "procesos";
$titulo_pagina = str_replace("-"," ",$esta_pagina);
$titulo_pagina = str_replace(".php","",$titulo_pagina);
$subtitulo = basename(__FILE__,'.php');
$_SESSION['titulo_pagina'] = $titulo_pagina;

$url = "../";
include $url.'funciones/mostrar-cabecera.php';

echo '<div class="top-header"';
	echo 'style="background-image: url('.$url.'iconos/activos/cabecera.jpg)">';
	echo '<div class="container">';
		echo '<h1>'.$titulo_pagina.'</h1>';
	echo '</div>';
echo '</div>';
echo '<div class="container">';
	echo '<section class="interna">';
		echo '<div class="row">';
			echo '<div class="col-sm-12">';
				$rangodias = 1;
				// include $url.'funciones/generar-persistencia.php';
				$titulo = basename(__FILE__,'.php');
				$i = 0;
				$ind = $i;
				include $url.'funciones/conectar-base-de-datos.php';
				include $url.'vistas/datos/organigrama.php';
				$campos = $_SESSION['campos'];

                $campo_a_modificar = "";
				if(isset($_GET['c'])) $campo_a_modificar = $_GET['c'];
				if(isset($_POST['modificar']))
				{
					if($campo_a_modificar == "documento_numero")
					{
						$valor_a_actualizar = str_replace(".", "", $_POST['campo_a_modificar']);
					}
					elseif($campo_a_modificar == "usuario" or $campo_a_modificar == "documento_tipo")
					{
						$valor_a_actualizar = strtolower($_POST['campo_a_modificar']);
					}
					else
					{
						$valor_a_actualizar = $_POST['campo_a_modificar'];
					}
					$consulta = 'UPDATE organigrama
					SET '.$campo_a_modificar.' = "'.$valor_a_actualizar.'"
					WHERE organigrama LIKE "'.$_SESSION['usuario_en_sesion'].'"';
					try
					{
					    
						$query = $conexion->prepare($consulta);
						$query->execute();
					    echo "<b style='color:green'>Se ha modificado con exito.</b>";
					}
					catch( PDOException $e )
					{
					    echo "<b style='color:red'>No se pudo modificar.</b>";
					}
				}
				
				$consulta_seleccion = 'SELECT * 
					FROM organigrama
					WHERE borrado LIKE "no"
					AND organigrama LIKE "'.$_SESSION['usuario_en_sesion'].'"
					GROUP BY organigrama
					ORDER BY organigrama
					ASC'
					;
				$query_seleccion = $conexion->prepare($consulta_seleccion);
				$query_seleccion->execute();
				while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC))
				{
					foreach ($campos as $campo_nombre => $campo_atributos)
					{
						$valores[$campo_nombre] = $rows_seleccion[$campo_nombre];
						
					}
				}
				
				echo '<form id="formModificarMiPerfil" action="" method="post">';
					echo '<div>';
						echo '<table>';
							foreach($valores as $campo => $valor)
							{
								if($campo == "email" or $campo == "documento_tipo" or $campo == "documento_numero" or $campo == "clave")
								{
									if(empty($valor)) $valor = "sin datos";
									echo '<tr>';
										echo '<th>';
											echo ucwords(str_replace("_", " ", $campo));
										echo '</th>';
									if(isset($_GET['c']) and $campo == $_GET['c'])
									{
										echo '<td>';
											if($campo == "clave")
											{
												echo '<input type="password" name="campo_a_modificar" id="'.$campo.'" value="'.$valor.'">';
												echo '&nbsp&nbsp<input type="checkbox" name="tipoDeClave" id="tipoDeClave" value="si"> Mostrar Clave';
											}
											elseif($campo == "documento_tipo")
											{
												$campo_atributo['herramientas'] = "agrupadores-descripcion-agrupador-tipos de documentos de identificacion";
												include $url.'funciones/seleccionar-archivos-especificos.php';
											}
											else
											{
												echo '<input type="text" name="campo_a_modificar" id="'.$campo.'" value="'.$valor.'">';
											}
										echo '</td>';
									}
									else
									{
										if($campo == "clave")
										{
											$largo = strlen($valor);
											echo '<td>';
												echo '<a href="'.$url.'funciones/modificar-mi-perfil.php?c='.$campo.'">';
													for ($i=0; $i < $largo+1; $i++) echo "*";
									        		echo '</a>';
											echo '</td>';
										}
										else
										{
											echo '<td>';
													echo '<a href="'.$url.'funciones/modificar-mi-perfil.php?c='.$campo.'">';
														echo $valor;
										        	echo '</a>';
											echo '</td>';
										}
									}
									echo '</tr>';
								}
							}
						echo '</table>';
					echo '</div>';
					//if(isset($_POST['modificar'])) echo '<a href="'.$url.'funciones/modificar-mi-perfil.php">Ver Cambios</a>';
					echo '<input type="submit" name="modificar" value="Modificar">';
					
				echo '</form>';
			echo '</div>';
		echo '</div>';
	echo '</section>';
echo '</div>';
include $url.'funciones/mostrar-pie.php';
echo '</body>';
echo '</html>';
?>
<script type="text/javascript">
	var campo_a_modificar = '<?php echo $campo_a_modificar; ?>';
	if(campo_a_modificar == "clave")
	{
		document.getElementById("tipoDeClave").onchange=function(){
			if(document.getElementById("tipoDeClave").checked)
			{
				 document.getElementById("clave").setAttribute('type', 'text');
			}
			else
			{
				document.getElementById("clave").setAttribute('type', 'password');
			}
		};
	}
	document.getElementById("formModificarMiPerfil").onsubmit=function(){
		return confirm("Desea cambiar el campo "+campo_a_modificar+" ?");
	};
</script>