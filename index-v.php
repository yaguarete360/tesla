<?php if (!isset($_SESSION)) {session_start();}

$url = "";
include 'funciones/mostrar-cabecera.php';

//echo phpinfo();

echo '<div id="slider-interna" class="carousel slide" data-ride="carousel">';
	
	$raiz = "slides";	
	$directorio = "acceso-principal";
	$orden = "des";
	include 'funciones/mostrar-slider.php';
	
	// echo '<div class="mensaje-especial">';
	// 	echo '<img src="imagenes/iconos/mensaje-especial.jpg">';
	// echo '</div>';

echo '</div>';
echo '<div class="container">';
	echo '<div class="row">';
		echo '<div class="col-md-5">';
			echo '<section class="home-section" style="overflow: hidden;">';
				echo '<header>';
					echo '<h2>Exequias</h2>';
				echo '</header>';
				echo '<article>';
					echo '<div class="exequias-list">';	

						include 'funciones/obtener-exequias.php';					
					
					echo '</div>';
					echo '<br/>';
					echo '<p><a href="vistas/sepelios/consultar-exequias.php" class="btn btn-primary btn-block">Consultar más...</a></p>';
				echo '</article>';
			echo '</section>';
		echo '</div>';
		echo '<div class="cuadro_contacto" id="enfocar_contacto">';
			echo '<span>';
				// echo '<img src="imagenes/iconos/whatsapp.png" alt="whatsapp_ventas" style="display:inline-block;">';
				echo '<h5 style="display:inline-block;">Contacto</h5>';
			echo '</span>';
		echo '</div>';
		echo '<div class="col-md-7">';
			echo '<section class="home-section" id="contacto_a_enfocar">';
				echo '<header>';
					echo '<h2>Contacto</h2>';
				echo '</header>';
				// echo '<br/>';
				echo '<article>';
					// echo '<div class="row">';
					// echo '</div>';
					echo '<div class="row">';
						echo '<div class="col-xs-12">';
							$estilo_th = 'color:#5a4a42;font-weight:bold;padding:5px;width:50%;';
							$estilo_td = 'padding:5px;width:50%;';
							echo '<table style="display:table;width:50%;margin:auto;">';
								echo '<tbody style="width:100%;">';
									echo '<tr>';
										echo '<td colspan="2">';
											echo '<span style="display:block;width:100%;margin:auto;text-align:center;">';
												echo '<h3>';
													echo 'Urgencias 24 horas';
												echo '</h3>';
											echo '</span>';
										echo '</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<th style="'.$estilo_th.'">';
											echo '<h5 style="padding:0px;margin:0px;">';
												echo 'Roberto:';
											echo '</h5>';
										echo '</th>';
										echo '<td style="'.$estilo_td.'">';
											echo '<h5 style="padding:0px;margin:0px;">';
												echo '+595982420042';
											echo '</h5>';
										echo '</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<th style="'.$estilo_th.'">';
											echo '<h5 style="padding:0px;margin:0px;">';
												echo 'Arnaldo:';
											echo '</h5>';
										echo '</th>';
										echo '<td style="'.$estilo_td.'">';
											echo '<h5 style="padding:0px;margin:0px;">';
												echo '+595981194394';
											echo '</h5>';
										echo '</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<th style="'.$estilo_th.'">';
											echo '<h5 style="padding:0px;margin:0px;">';
												echo 'Gustavo:';
											echo '</h5>';
										echo '</th>';
										echo '<td style="'.$estilo_td.'">';
											echo '<h5 style="padding:0px;margin:0px;">';
												echo '+595971249393';
											echo '</h5>';
										echo '</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<th style="'.$estilo_th.'">';
											echo '<h5 style="padding:0px;margin:0px;">';
												echo 'Luis:';
											echo '</h5>';
										echo '</th>';
										echo '<td style="'.$estilo_td.'">';
											echo '<h5 style="padding:0px;margin:0px;">';
												echo '+595982113237';
											echo '</h5>';
										echo '</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<td colspan="2">';
											echo '<span style="display:block;width:100%;margin:auto;text-align:center;">';
												echo '<h3>';
													echo 'Contacto Inmediato';
												echo '</h3>';
											echo '</span>';
										echo '</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<th style="'.$estilo_th.'">';
											echo '<h5 style="padding:0px;margin:0px;">';
												echo 'Central Telefonica:';
											echo '</h5>';
										echo '</th>';
										echo '<td style="'.$estilo_td.'">';
											echo '<h5 style="padding:0px;margin:0px;">';
												echo '+595981551129';
											echo '</h5>';
										echo '</td>';
									echo '</tr>';
									echo '<tr>';
										echo '<th style="'.$estilo_th.'">';
											echo '<h5 style="padding:0px;margin:0px;">';
												echo 'Directo de Ventas y Whatsapp:';
											echo '</h5>';
										echo '</th>';
										echo '<td style="'.$estilo_td.'">';
											echo '<h5 style="padding:0px;margin:0px;">';
												// echo '<a href="https://api.whatsapp.com/send?phone=+595986106382" target="_blank">';
													echo '+595986106382';
												// echo '</a>';
											echo '</h5>';
										echo '</td>';
									echo '</tr>';
								echo '</tbody>';
							echo '</table>';
							echo '<hr>';
							if(isset($_POST['contactar']))
							{
								include 'funciones/conectar-base-de-datos.php';

								$client  = @$_SERVER['HTTP_CLIENT_IP'];
							    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
							    $remote  = $_SERVER['REMOTE_ADDR'];
							    
							    if(filter_var($client, FILTER_VALIDATE_IP))
							    {
							        $direccion_ip = $client;
							    }
							    elseif(filter_var($forward, FILTER_VALIDATE_IP))
							    {
							        $direccion_ip = $forward;
							    }
							    else
							    {
							        $direccion_ip = $remote;
							    }
								$consulta_insertar = 'INSERT INTO solicitudes_contactos (solicitud, solicitud_telefono, solicitud_mail, usuario, creado, borrado) VALUES 
									("'.$_POST['contacto_nombre'].'", "'.$_POST['contacto_telefono'].'", "'.$_POST['contacto_mail'].'", "'.$direccion_ip.'", "'.date('Y-m-d G:i:s').'", "no")';
								$query_insertar = $conexion->prepare($consulta_insertar);
                    			$query_insertar->execute();

                    			$guardo_ok = false;
                    			$consulta_controlar_insercion = 'SELECT id FROM solicitudes_contactos WHERE borrado = "no" 
                    				AND solicitud = "'.$_POST['contacto_nombre'].'"
                    				AND solicitud_telefono = "'.$_POST['contacto_telefono'].'"
                    				AND solicitud_mail = "'.$_POST['contacto_mail'].'"';
                    			$query_controlar_insercion = $conexion->prepare($consulta_controlar_insercion);
                    			$query_controlar_insercion->execute();
                    			while($rows_controlar_insercion = $query_controlar_insercion->fetch(PDO::FETCH_ASSOC)) $guardo_ok = true;

                				echo '<span style="display:block;width:100%;margin:auto;text-align:center;">';
	                    			if($guardo_ok)
	                    			{
										echo 'Gracias por contactarnos, un asesor se comunicara con usted en la brevedad posible para más información.';
	                    			}
	                    			else
	                    			{
										echo 'No se pudo guardar su solicitud. Favor intentar nuevamente.';
	                    			}
								echo '</span>';
							}
							else
							{
								echo '<span style="display:block;width:100%;margin:auto;text-align:center;">';
									echo 'Completando sus datos aquí, un asesor se comunicara con usted en la brevedad posible para más información.';
								echo '</span>';
								echo '<br/>';
								echo '<form method="post" action="">';
									echo '<table style="display:table;width:75%;margin:auto;">';
										echo '<tbody style="width:100%;">';
											echo '<tr style="width:100%;">';
												echo '<th style="'.$estilo_th.'">';
													echo 'Nombre';
												echo '</th>';
												echo '<td style="'.$estilo_td.'">';
													echo '<input type="text" name="contacto_nombre" value="" style="width:100%">';
												echo '</td>';
											echo '</tr>';
											echo '<tr>';
												echo '<th style="'.$estilo_th.'">';
													echo 'Número de Teléfono';
												echo '</th>';
												echo '<td style="'.$estilo_td.'">';
													echo '<input type="text" name="contacto_telefono" value="" style="width:100%">';
												echo '</td>';
											echo '</tr>';
											echo '<tr>';
												echo '<th style="'.$estilo_th.'">';
													echo 'Correo Electrónico';
												echo '</th>';
												echo '<td style="'.$estilo_td.'">';
													echo '<input type="text" name="contacto_mail" value="" style="width:100%">';
												echo '</td>';
											echo '</tr>';
											echo '<tr>';
												echo '<td colspan="2">';
													echo '<input type="submit" class="btn btn-primary btn-block" name="contactar" value="Solicitar Contacto">';
												echo '</td>';
											echo '</tr>';
										echo '</tbody>';
									echo '</table>';
								echo '</form>';
							}
							// echo '<p><a href="vistas/quienes-somos/nuestra-gente.php"';
							// echo 'class="btn btn-primary btn-block">Conozca a nuestra gente</a></p>';
						echo '</div>';
					echo '</div>';
				echo '</article>';
			echo '</section>';
			// echo '<section class="home-section">';
			// 	echo '<header>';
					
			// 		include 'funciones/conectar-base-de-datos.php';
					
			// 		$titulo = "QUIENES SOMOS";
			// 		$descripcion = '
			// 		<b><center>
			// 		La vida es un homenaje. La vida es amor. Amor en los recuerdos.</b><br/>
			// 		</center>
			// 		<br/>
			// 		<br/>
			// 		Somos <b>PARQUE SERENIDAD MEMORIAL</b>, un equipo humano y profesional, con
			// 		la mejor infraestructura, para proveer todo lo necesario, asesorar y acompañar a 
			// 		las familias en la ceremonia mas importante y dificil de la vida.
			// 		<br/>
			// 		<br/>
			// 		Con más de <b>30 años</b> de trayectoria, nuestra empresa es la PRIMERA EMPRESA 
			// 		INTEGRAL DE SEPELIOS del Paraguay, creada con el objetivo de dignificar la ceremonia 
			// 		de sepelio de nuestros seres queridos y proveer un espacio en armonía con la naturaleza 
			// 		donde recordarlos con serenidad y amor. 
			// 		<br/>
			// 		<br/>
			// 		Antes de <b>PARQUE SERENIDAD</b>, hasta la década de 1970, para el velatorio y entierro de un ser querido, 
			// 		la sociedad paraguaya debía recurrir a funerarias que proveían solamente el féretro y la carroza.
			// 		De todas las demás necesidades se debían encargar los deudos... 
			// 		<a href="vistas/quienes-somos/trayectoria.php"><span class="link-beige"> Ver mas + </span></a>
			// 		<br/>
			// 		<br/>
			// 		<br/>
			// 		';						
					
			// 		echo '<h2>'.$titulo.'</h2>';
			// 	echo '</header>';
			// 	echo '<br/>';
			// 	echo '<article>';
			// 		echo '<div class="row">';
			// 		echo '</div>';
			// 		echo '<div class="row">';
			// 			echo '<div class="col-xs-12">';
			// 				echo $descripcion;
			// 				echo '<p><a href="vistas/quienes-somos/nuestra-gente.php"'; 
			// 				echo 'class="btn btn-primary btn-block">Conozca a nuestra gente</a></p>';							
			// 			echo '</div>';
			// 		echo '</div>';
			// 	echo '</article>';
			// echo '</section>';
		echo '</div>';
	echo '</div>';
echo '</div>';

include $url.'funciones/mostrar-pie.php';

echo '</body>';
echo '</html>';

/*******************PROMOCION********************/
// echo '<div class="modal fade mymodal" id="promocion" tabindex="-1" role="dialog" aria-labelledby="promocion" aria-hidden="true">';
//     echo '<div class="modal-dialog">';
//         echo '<div class="modal-content">';
//             echo '<div class="modal-header">';
// 	            echo '<div class="icono">';
// 	              // echo '<IMG SRC="imagenes/iconos/logo-pdf.png" WIDTH=300 HEIGHT=180 ALT="Obra de K. Haring">';
// 	            	echo '<a href="https://api.whatsapp.com/send?phone=+595986106382" target="_blank">';
// 	              		echo '<img src="imagenes/iconos/mensaje-especial.jpg"  alt="Promocion">'; // width=750
// 	            	echo '</a>';
// 	            echo '</div>';  
//             echo '</div>';
//             // echo '<div class="modal-body">';
//             //    echo '<div id="msg">Si sos cliente de <strong>PARQUE SERENIDAD</strong> actualiza tus datos!</div>';
//             // echo '</div>';
//             // echo '<div class="modal-footer">';
// 	           //   echo '<form action="funciones/actualizar-datos.php" method="POST">';
// 	           //      echo '<button type="button" class="btn btn-primary" data-dismiss="modal">No</button>&nbsp&nbsp';
// 	           //      echo '<input type="hidden" name="myValue" id="myValue" value=""/>';
// 	           //      echo '<button class="btn btn-primary" data-title="Actualizar">Actualizar Datos</button>';
//             //     echo '</form>';
//             // echo '</div>';
//         echo '</div>';
//     echo '</div>';
// echo '</div>';

?>

<script type="text/javascript">

	$('#enfocar_contacto').click(function()
	{
		$('html, body').animate({
            scrollTop: $("#contacto_a_enfocar").offset().top
        }, 500);
	});

    (function($){

    $(document).ready(function (){
         var session = "<?php echo $_SESSION['alias_en_sesion']?>";
    	 //alert(session);
    	if(session==''){
    	 
          $('#promocion').modal('toggle');
    	}else{
    		console.log('web privada...');
    	}  

    });

})(jQuery);

	
</script>

<style type="text/css">
	#msg{
		font-size: 20px;
	}
	.icono{
		margin-left: 24%;
	}

	.cuadro_contacto
	{
		position: fixed;
		bottom: 0px;
		right: 5%;
		/*border-top: 1px solid #D8A262;
		border-left: 1px solid #D8A262;
		border-right: 1px solid #D8A262;*/
		border-collapse: collapse;
		z-index: 100000;
		background-color: #D8A262;
		color: white;
		padding: 5px 15px 5px 15px;
	}

	.cuadro_contacto:hover
	{
		background-color: #418F47;
		cursor: pointer;
	}

</style>