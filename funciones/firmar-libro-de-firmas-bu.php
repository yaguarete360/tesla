<?php if (!isset($_SESSION)) {session_start();}

if(isset($_SESSION['alias_en_sesion']) and !empty($_SESSION['alias_en_sesion']))
{
	ini_set('display_errors', 1);
}

$url = "../";
include $url.'funciones/conectar-base-de-datos.php';

$titulo = 'Libro de Firmas';
// if(isset($_GET['n']) and !empty($_GET['n']))
// {
	$difunto_nombre_limpio_1 = str_replace('%2C', ',', $_GET['n']);
	$difunto_nombre_limpio_1 = str_replace('%20', ' ', $difunto_nombre_limpio_1);
	$difunto_nombre_limpio_1 = str_replace('_', ',', $difunto_nombre_limpio_1);
	$difunto_nombre_limpio = str_replace(' ,', ',', preg_replace('/\s+/', ' ', $difunto_nombre_limpio_1));

	$difunto_nombre_convertido_1 = preg_replace('/\s+/', ' ', implode(' ', array_reverse(explode(',', $difunto_nombre_limpio_1))));
// 	// $titulo = 'En memoria de '.ucwords($difunto_nombre_convertido_1);
	$titulo = ucwords($difunto_nombre_convertido_1);

// 	$reenviar_a_enviar_condolencias = true;
// 	$consulta_difuntos = 'SELECT id FROM difuntos WHERE borrado = "no" AND tipo = "sepelio" AND difunto = "'.$difunto_nombre_limpio.'" ORDER BY id DESC LIMIT 1';
// 	// echo $consulta_difuntos.'<br/>';
// 	$query_difuntos = $conexion->prepare($consulta_difuntos);
//     $query_difuntos->execute();
//     while($rows_difuntos = $query_difuntos->fetch(PDO::FETCH_ASSOC)) $reenviar_a_enviar_condolencias = false;

// 	if($reenviar_a_enviar_condolencias)
// 	{
// 		header('Location: https://www.parqueserenidad.com/funciones/enviar-email-1-condolencias.php?difunto_del_listado='.$difunto_nombre_limpio.'&url=../');
// 	}
// }

$poder_borrar = false;
if(isset($_SESSION['alias_en_sesion']) and !empty($_SESSION['alias_en_sesion']))
{
	$consulta_alias = ' SELECT * FROM permisos
		WHERE borrado = "no"
		AND alias = "'.$_SESSION['alias_en_sesion'].'"
		AND permiso = "procesos-difuntos-manejar_libros_de_firmas_virtuales"';
	$query_alias = $conexion->prepare($consulta_alias);
	$query_alias->execute();
	while($rows = $query_alias->fetch(PDO::FETCH_ASSOC)) $poder_borrar = true;
	if($_SESSION['alias_en_sesion'] == 'admin') $poder_borrar = true;
}

if(isset($_GET['id_a_b']) and $poder_borrar)
{
	$consulta_borrar = 'UPDATE condolencias SET borrado = "si", usuario = "'.$_SESSION['usuario_en_sesion'].'" WHERE borrado = "no" AND id = "'.($_GET['id_a_b']+0).'"';
	// echo $consulta_borrar.'<br/>';
	$query_borrar = $conexion->prepare($consulta_borrar);
    $query_borrar->execute();
    header('Location: https://www.parqueserenidad.com/funciones/firmar-libro-de-firmas.php?n='.$difunto_nombre_limpio);
}

if(isset($_GET['id_a_c']) and $poder_borrar)
{
	$consulta_c = ' SELECT * FROM condolencias
		WHERE borrado = "no"
		AND id = "'.($_GET['id_a_c']+0).'"
		LIMIT 1
		';
	$query_c = $conexion->prepare($consulta_c);
	$query_c->execute();
	while($rows_c = $query_c->fetch(PDO::FETCH_ASSOC))
	{
		$consulta_modificar = 'UPDATE condolencias 
			SET condolencia_nombre = "'.str_replace('"', "'", $rows_c['condolencia_texto']).'", 
				condolencia_texto = "'.str_replace('"', "'", $rows_c['condolencia_nombre']).'" 
			WHERE borrado = "no" AND id = "'.$rows_c['id'].'"';
		$query_modificar = $conexion->prepare($consulta_modificar);
	    $query_modificar->execute();
	    header('Location: https://www.parqueserenidad.com/funciones/firmar-libro-de-firmas.php?n='.$difunto_nombre_limpio);
	}

}

include $url.'funciones/mostrar-cabecera.php';

echo '<div class="top-header"';
	echo 'style="background-image: url('.$url.'imagenes/iconos/cabecera.jpg)">';
	// echo '<div class="container">';
		// echo '<h4 style="margin-bottom:0px;color:white;">En memoria de</h4>';
		// echo '<h1 style="margin-top:0px;">'.$titulo.'</h1>';
	// echo '</div>';
echo '</div>';
echo '<div class="container">';
	echo '<section class="interna" style="margin-top:-160px;">';
		echo '<div class="row">';
			echo '<div class="col-sm-12">';
				
				function correctImageOrientation($filename) {
				  if (function_exists('exif_read_data')) {
				    $exif = exif_read_data($filename);
				    if($exif && isset($exif['Orientation'])) {
				      $orientation = $exif['Orientation'];
				      if($orientation != 1){
				        $img = imagecreatefromjpeg($filename);
				        $deg = 0;
				        switch ($orientation) {
				          case 3:
				            $deg = 180;
				            break;
				          case 6:
				            $deg = 270;
				            break;
				          case 8:
				            $deg = 90;
				            break;
				        }
				        if ($deg) {
				          $img = imagerotate($img, $deg, 0);        
				        }
				        // then rewrite the rotated image back to the disk as $filename 
				        imagejpeg($img, $filename, 95);
				      } // if there is some rotation necessary
				    } // if have the exif orientation info
				  } // if function exists      
				}

				// function translate($from_lan, $to_lan, $text){
				//     $json = json_decode(file_get_contents('https://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q=' . urlencode($text) . '&langpair=' . $from_lan . '|' . $to_lan));
				//     $translated_text = $json->responseData->translatedText;

				//     return $translated_text;
				// }
				
				// move_uploaded_file($uploadedFile, $destinationFilename);
				// correctImageOrientation($destinationFilename);
				$mostrar_exequias = false;
				if(isset($_GET['n']) and !empty($_GET['n']))
				{
					// $difunto = $_GET['n'];
					$difunto = str_replace(' ,', ',', preg_replace('/\s+/', ' ', str_replace('%2C', ',', str_replace('_', ',', str_replace('%20', ' ', $_GET['n'])))));
					$codigo = '';
					$nacimiento = '';
					$defuncion = '';
					$id_de_carga_imagen = 0;
					$fecha_filtro = date('Y-m-d', strtotime(date('Y-m-d').' - 30 days')); // AND fecha > "'.$fecha_filtro.'"
					$consulta_difuntos = 'SELECT codigo, nacimiento, defuncion_fecha FROM difuntos WHERE borrado = "no" AND difunto = "'.$difunto.'" ORDER BY id ASC LIMIT 1'; // AND tipo = "sepelio"
					
					$query_difuntos = $conexion->prepare($consulta_difuntos);
                    $query_difuntos->execute();
                    
                    if(isset($_SESSION['alias_en_sesion']) and !empty($_SESSION['alias_en_sesion'])) echo 'prueba='.$_GET['n'].'<br/>';
                    if(isset($_SESSION['alias_en_sesion']) and !empty($_SESSION['alias_en_sesion'])) echo $consulta_difuntos.'<br/>';

                    while($rows_difuntos = $query_difuntos->fetch(PDO::FETCH_ASSOC))
                	{
                		$codigo = $rows_difuntos['codigo'];
                		$nacimiento = $rows_difuntos['nacimiento'];
						$defuncion = $rows_difuntos['defuncion_fecha'];
                	}
                    // echo $codigo.'<br/>';
                    $existe_condolencia = false;
                    $inserto_exitosamente = false;
                    if(!empty($codigo))
                    {
						if(isset($_POST['enviar_condolencias']))
						{
							$condolencia_nombre = preg_replace('/\s+/', ' ', $_POST['condolencia_nombre']);
							$condolencia_nombre = trim($condolencia_nombre);
							$condolencia_nombre = strtolower($condolencia_nombre);
							$condolencia_nombre = str_replace('Á', 'á', $condolencia_nombre);
							$condolencia_nombre = str_replace('É', 'é', $condolencia_nombre);
							$condolencia_nombre = str_replace('Í', 'í', $condolencia_nombre);
							$condolencia_nombre = str_replace('Ó', 'ó', $condolencia_nombre);
							$condolencia_nombre = str_replace('Ú', 'ú', $condolencia_nombre);

							$condolencia_texto = trim($_POST['condolencia_texto']);
							// $condolencia_imagen = $_POST['condolencia_imagen'];

							// var_dump($condolencia_nombre);
							// echo '<br/>';
							// var_dump($condolencia_texto);
							// echo '<br/>';

							if(!empty($condolencia_nombre) and !empty($condolencia_texto))
							{
								$consulta_control_existencia = 'SELECT id FROM condolencias 
									WHERE borrado = "no" 
										AND condolencia_nombre = "'.$condolencia_nombre.'" 
										AND condolencia_texto = "'.$condolencia_texto.'" 
										AND servicio_codigo = "'.$codigo.'"
									ORDER BY id ASC LIMIT 1';
								// echo $consulta_control_existencia.'<br/>';
								$query_control_existencia = $conexion->prepare($consulta_control_existencia);
			                    $query_control_existencia->execute();
			                    while($rows_control_existencia = $query_control_existencia->fetch(PDO::FETCH_ASSOC)) $existe_condolencia = true;

			                    if(!$existe_condolencia)
			                    {
									$consulta_insercion = 'INSERT INTO condolencias (condolencia, servicio_codigo, condolencia_nombre, condolencia_texto, usuario, creado, borrado) VALUES (
										"'.$difunto.'",
										"'.$codigo.'",
										"'.$condolencia_nombre.'",
										"'.$condolencia_texto.'",
										"proceso condolencias",
										"'.date('Y-m-d G:i:s').'",
										"no"
										)';
									$query_insercion = $conexion->prepare($consulta_insercion);
			                    	$query_insercion->execute();
			                    	// echo $consulta_insercion.'<br/>';

			                    	$consulta_control_existencia = 'SELECT id FROM condolencias 
										WHERE borrado = "no" 
											AND condolencia_nombre = "'.$condolencia_nombre.'" 
											AND condolencia_texto = "'.$condolencia_texto.'" 
											AND servicio_codigo = "'.$codigo.'"
										ORDER BY id ASC LIMIT 1';
									$query_control_existencia = $conexion->prepare($consulta_control_existencia);
				                    $query_control_existencia->execute();
				                    // echo $consulta_control_existencia.'<br/>';
				                    while($rows_control_existencia = $query_control_existencia->fetch(PDO::FETCH_ASSOC))
			                    	{
			                    		$existe_condolencia = true;
			                    		$inserto_exitosamente = true;
			                    		$id_de_carga_imagen = str_pad($rows_control_existencia['id'], 7, '0', STR_PAD_LEFT);
			                    	}
			                    	// var_dump($inserto_exitosamente);
			                    	// echo '<br/>';

			                    	if($inserto_exitosamente and isset($_FILES["condolencia_imagen"]) and !empty($_FILES["condolencia_imagen"]['name']))
			                    	{
				                    	$ruta_banco_de_fotos = '../imagenes/condolencias/';
				                        $extension_de_la_imagen = explode('.', $_FILES["condolencia_imagen"]["name"]);
				                        // var_dump($_FILES["condolencia_imagen"]);
					                    if(move_uploaded_file($_FILES["condolencia_imagen"]["tmp_name"], $ruta_banco_de_fotos.$codigo.'-'.$id_de_carga_imagen.'.'.strtolower(end($extension_de_la_imagen))))
					                    {
					                    	$destino_a_rotar = $ruta_banco_de_fotos.$codigo.'-'.$id_de_carga_imagen.'.'.strtolower(end($extension_de_la_imagen));
					                    	if(strtolower(end($extension_de_la_imagen)) != 'png') correctImageOrientation($destino_a_rotar);
					                        echo "El archivo ha sido subido correctamente.";
					                    }
					                    else
					                    {
					                        echo "<b>Ha ocurrido un error. No se pudo subir el archivo.</b><br/>";
					                    }
			                    	}
			                    }
							}
						}
                    	// var_dump($existe_condolencia);
                    	// echo '<br/>';

						$condolencias = array();
	                	$consulta_condolencias = 'SELECT * FROM condolencias WHERE borrado = "no" AND condolencia = "'.$difunto.'" ORDER BY id DESC';
	                	// echo $consulta_condolencias.'<br/>';
						$query_condolencias = $conexion->prepare($consulta_condolencias);
	                    $query_condolencias->execute();
	                    while($rows_condolencias = $query_condolencias->fetch(PDO::FETCH_ASSOC))
	                	{
	                		$id = str_pad($rows_condolencias['id'], 7, '0', STR_PAD_LEFT);
	                		$condolencias[$id]['nombre'] = $rows_condolencias['condolencia_nombre'];
	                		$condolencias[$id]['texto'] = $rows_condolencias['condolencia_texto'];
	                	}

	                	$poder_borrar = false;
	                	if(isset($_SESSION['alias_en_sesion']) and !empty($_SESSION['alias_en_sesion']))
	                	{
		                	$consulta_alias = ' SELECT * FROM permisos
								WHERE borrado = "no"
								AND alias = "'.$_SESSION['alias_en_sesion'].'"
								AND permiso = "procesos-difuntos-manejar_libros_de_firmas_virtuales"';
							$query_alias = $conexion->prepare($consulta_alias);
							$query_alias->execute();
							while($rows = $query_alias->fetch(PDO::FETCH_ASSOC)) $poder_borrar = true;
							if($_SESSION['alias_en_sesion'] == 'admin') $poder_borrar = true;
	                	}

	                	echo '<table class="condolencias_tabla">';
	                		echo '<tr>';
	                			echo '<tr>';
	                				echo '<td>';
	                					$ruta_imagen = '../imagenes/condolencias/'.$codigo.'-0000000';
	                					$foto_archivo_principal = '';
	                					$tiene_foto_principal = false;
	                					$tipos_de_imagenes = array('jpg', 'jpeg', 'png');
	                					$extension_de_la_imagen = '';
	                					foreach ($tipos_de_imagenes as $extension)
	                					{
                							// echo $ruta_imagen.'.'.$extension.'<br/>';
	                						if(file_exists($ruta_imagen.'.'.$extension))
	                						{
	                							$foto_archivo_principal = $ruta_imagen.'.'.$extension;
	                							$extension_de_la_imagen = $extension;
	                							$tiene_foto_principal = true;
	                						}
	                					}
	                					// var_dump($tiene_foto_principal);

	                					if($tiene_foto_principal)
                						{
					                    	if(strtolower($extension_de_la_imagen) != 'png') correctImageOrientation($foto_archivo_principal);
                							echo '<img class="condolencias_foto_principal" src="'.$foto_archivo_principal.'">';
                						}
	                				echo '</td>';
	                			echo '</tr>';
	                			echo '<tr>';
	                				echo '<td style="height:30px;">';
	                					echo 'Parque Serenidad participa con mucho pesar la partida de';
	                					echo '<h3 style="margin-top:10px;">'.ucwords($difunto_nombre_convertido_1).'</h3>';
	                					if(!empty($nacimiento)) echo '<img src="../imagenes/iconos/estrella-nacimiento.png" height="20">&nbsp&nbsp'.$nacimiento;
	                					if(!empty($nacimiento) and !empty($defuncion)) echo '&nbsp&nbsp&nbsp&nbsp';
	                					if(!empty($defuncion)) echo '<img src="../imagenes/iconos/cruz-defuncion.png" height="20">&nbsp&nbsp'.$defuncion;
										echo '<br/><br/>Acompañando a familiares y amigos en este momento de dolor ante tan grande perdida, los invitamos a compartir unas palabras en su honor.';
	                					echo '<hr>';
	                				echo '</td>';
	                			echo '</tr>';
	                		echo '</tr>';
		                	foreach ($condolencias as $id => $condolencia_campos)
		                	{
	                			echo '<tr>';
		                			echo '<td>';
		                				echo '<h4 style="text-transform:capitalize;">';
		                					echo strtolower($condolencia_campos['nombre']);
		                					if($poder_borrar)
	                						{
	                							echo '&nbsp&nbsp<a href="?n='.$difunto.'&id_a_c='.$id.'"><img src="../imagenes/iconos/boton-flechas-intercambio.png" style="height:15px;"></a>';
	                							echo '&nbsp&nbsp<a href="?n='.$difunto.'&id_a_b='.$id.'"><img src="../imagenes/iconos/boton-bajas.png" style="height:15px;"></a>';
											}
	                					echo '</h4>';
		                				// echo '<h4>'.ucwords(strtolower($condolencia_campos['nombre'])).'</h4>';
		                				// mb_convert_case($condolencia_campos['nombre'], MB_CASE_TITLE, "UTF-8");
		                				// echo '<img src="../imagenes/iconos/guion.png" height="20">&nbsp&nbsp';
		                				echo '<div class="contenedor_texto">';
		                					echo $condolencia_campos['texto'];
		                				echo '</div>';


		                				$ruta_imagen = '../imagenes/condolencias/'.$codigo.'-'.$id;
	                					$foto_archivo = '';
	                					$tiene_foto = false;
	                					$tipos_de_imagenes = array('jpg', 'jpeg', 'png');
	                					$extension_de_la_imagen = '';
	                					foreach ($tipos_de_imagenes as $extension)
	                					{
	                						// echo 'ruta_imagen='.$ruta_imagen.'.'.$extension.'<br/>';
	                						// var_dump(file_exists($ruta_imagen.'.'.$extension));
	                						// echo '<br/>';
	                						if(file_exists($ruta_imagen.'.'.$extension))
	                						{
	                							$foto_archivo = $ruta_imagen.'.'.$extension;
	                							$tiene_foto = true;
	                							$extension_de_la_imagen = $extension;
	                						}
	                					}
	                					echo '<br/>';
	                					if($tiene_foto)
                						{
                							if(strtolower($extension_de_la_imagen) != 'png') correctImageOrientation($foto_archivo);
                							echo '<img class="condolencias_foto_secundaria" src="'.$foto_archivo.'">';
                						}
		                				echo '<hr>';
		                			echo '</td>';
	                			echo '</tr>';
		                	}
	                	echo '</table>';

	                	$condolencia_nombre_defecto = (isset($_POST['condolencia_nombre']) and !$inserto_exitosamente) ? $_POST['condolencia_nombre'] : '';
	                	$condolencia_texto_defecto = (isset($_POST['condolencia_texto']) and !$inserto_exitosamente) ? $_POST['condolencia_texto'] : '';

	                	echo '<div class="agregar_condolencias">';
	                		echo '<form method="post" action="" id="form_enviar_condolencias" enctype="multipart/form-data">';
		                		echo '<table>';
		                			echo '<tr>';
		                				echo '<td colspan="2" style="text-align:center;">';
	                						if($tiene_foto_principal and count($condolencias) > 10)
                							{
                								// correctImageOrientation($foto_archivo_principal);
                								echo '<img class="condolencias_foto_principal" src="'.$foto_archivo_principal.'">';
                							}
		                				echo '</td>';
		                			echo '</tr>';
		                			echo '<tr>';
		                				echo '<td colspan="2" style="text-align:center;">';
		                					echo '<h5>Deje Su Mensaje</h5>';
		                				echo '</td>';
		                			echo '</tr>';
		                			echo '<tr>';
		                				echo '<td>';
		                					echo 'Nombre';
		                				echo '</td>';
		                				echo '<td>';
		                					echo '<input type="text" name="condolencia_nombre" id="condolencia_nombre" value="'.$condolencia_nombre_defecto.'" style="width:100%;">';
		                				echo '</td>';
		                			echo '</tr>';
		                			echo '<tr>';
		                				echo '<td>';
		                					echo 'Mensaje';
		                				echo '</td>';
		                				echo '<td>';
		                					echo '<textarea name="condolencia_texto" id="condolencia_texto" style="width:100%;">';
		                						echo $condolencia_texto_defecto;
		                					echo '</textarea>';
		                				echo '</td>';
		                			echo '</tr>';
		                			echo '<tr>';
		                				echo '<td>';
		                					echo 'Imagen';
		                				echo '</td>';
		                				echo '<td>';
		                					echo '<input type="file" name="condolencia_imagen" id="condolencia_imagen" style="display:inline-block;width:100%;" placeholder="Subir Imagen" accept="image/*">';
		                				echo '</td>';
		                			echo '</tr>';
		                			echo '<tr>';
		                				echo '<td colspan="2" id="td_submit">';
		                					echo '<input type="submit" name="enviar_condolencias" id="enviar_condolencias" value="Enviar" class="submit_aprobar">';
		                				echo '</td>';
		                			echo '</tr>';
		                		echo '</table>';
	                		echo '</form>';
	                	echo '</div>';
                    }
                    else
                    {
                    	$mostrar_exequias = true;
                    }
				}
				else
				{
                	$mostrar_exequias = true;
				}

				if($mostrar_exequias)
				{
					echo '<div class="col-md-5" style="width: 100%;">';
						echo '<section class="home-section" style="overflow: hidden;box-shadow: none;">';
							echo '<header>';
								echo '<h2>Exequias</h2>';
							echo '</header>';
							echo '<article>';
								echo '<div class="exequias-list">';	

									include './obtener-exequias.php';
								
								echo '</div>';
								echo '<br/>';
								echo '<p><a href="'.$url.'vistas/sepelios/consultar-exequias.php" class="btn btn-primary btn-block">Consultar más...</a></p>';
							echo '</article>';
						echo '</section>';
					echo '</div>';
				}

			echo '</div>';
		echo '</div>';
	echo '</section>';
echo '</div>';

include $url.'funciones/mostrar-pie.php';

echo '<div class="icono_escribir">';
	echo '<a href="#form_enviar_condolencias">';
		echo '<img src="../imagenes/iconos/escribir.png">';
	echo '</a>';
echo '</div>';

echo '</body>';

echo '</html>';

?>

<script>
	
	$('#enviar_condolencias').on('click', function(){
        $( "#td_submit" ).append('<div class="div_cargando"><img class="barra_loading" src="../../imagenes/iconos/loading.gif"></div>');
    });

    $('.icono_escribir').fadeOut(50);

    $(document).ready(function()
    {
    	$('.icono_escribir').delay(5000).fadeIn();
	});

	$('.icono_escribir').on('click', 'a[href^="#"]', function (event) {
	    event.preventDefault();

	    $('html, body').animate({
	        scrollTop: $($.attr(this, 'href')).offset().top
	    }, 500);

	    $('#condolencia_nombre').focus();
	    $('.icono_escribir').fadeOut();
	});

	$('#condolencia_nombre,#condolencia_texto').focusin(function()
	{
		$('.icono_escribir').fadeOut();
	});

	$('#condolencia_nombre,#condolencia_texto').focusout(function()
	{
		$('.icono_escribir').fadeIn();
	});

	// $("..").is(":focus")

</script>
