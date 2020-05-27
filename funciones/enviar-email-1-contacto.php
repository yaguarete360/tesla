<?php if (!isset($_SESSION)) {session_start();}

$url = "../";

$retroceder = "";

$esta_vista = "Contactar";

include $url.'funciones/mostrar-cabecera.php';

echo '<div class="top-header"';
	echo 'style="background-image: url('.$url.'imagenes/iconos/cabecera.jpg)">';
	echo '<div class="container">';
		echo '<h1>'.$titulo.'</h1>';
	echo '</div>';
echo '</div>';
echo '<div class="container">';
	echo '<section class="interna">';
		echo '<div class="row">';
			echo '<div class="col-md-8">';
				echo '<h4 class="title">Formulario de contacto</h4>';
				echo '<form action="'.$url.'funciones/enviar-email-4-enviar.php" method="post"/>';
					echo '<div class="row">';
						echo '<div class="col-sm-6">';
							echo '<div class="form-group">';
								echo '<label for="motivo">Motivo</label>';
								echo '<select name="motivo" id="motivo" class="form-control">';
									echo '<option value="0">Seleccionar</option>';
									echo '<option value="1">Consulta</option>';
									echo '<option value="2">Ventas</option>';
									echo '<option value="3">Reclamo</option>';
									echo '<option value="4">Sugerencia</option>';
									echo '<option value="5">Varios</option>';
								echo '</select>';
							echo '</div>';
						echo '</div>';
						echo '<div class="col-sm-6">';
							echo '<div class="form-group">';
								echo '<label for="servicio">Servicio</label>';
								echo '<select name="servicio" id="servicio" class="form-control">';
									echo '<option value="0">Seleccionar</option>';
									echo '<option value="1">Servicio de Sepelios</option>';
									echo '<option value="2">Crematorio</option>';
									echo '<option value="3">Cementerios Parque</option>';
									echo '<option value="4">Prepagas de Sepelio y Cremación</option>';
									echo '<option value="5">Féretros</option>';
									echo '<option value="6">Vehículos</option>';
								echo '</select>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
					echo '<div class="form-group">';
						echo '<label for="disponibilidad">Disponibilidad</label>';
						echo '<div class="row">';
							echo '<div class="col-lg-6">';
								echo '<div class="form-group">';
									echo '<b> De </b><select name="dia_1" id="day1" class="day">';
										echo '<option value="1">Lunes</option>';
										echo '<option value="2">Martes</option>';
										echo '<option value="3">Miércoles</option>';
										echo '<option value="4">Jueves</option>';
										echo '<option value="5">Viernes</option>';
										echo '<option value="6">Sábados</option>';
									echo '</select>';
									echo '<span class="fix-formgroup">';
										echo '<b> a </b><select name="dia_2" id="day2" class="day">';
											echo '<option value="1">Lunes</option>';
											echo '<option value="2">Martes</option>';
											echo '<option value="3">Miércoles</option>';
											echo '<option value="4">Jueves</option>';
											echo '<option value="5">Viernes</option>';
											echo '<option value="6">Sábados</option>';
										echo '</select>';
									echo '</span>';
								echo '</div>';
							echo '</div>';
							echo '<div class="col-lg-6">';
								echo '<div class="form-group">';
									echo '<b>De </b><select name="hora_1" id="day1" class="day">';
										for($hora = 0; $hora < 24; $hora++)
										{
											echo '<option value="'.$hora.'">'.$hora.'</option>';
										}
									echo '</select>';
										echo '<b> a </b><select name="hora_2" id="day2" class="day">';
										for($hora = 0; $hora < 24; $hora++)
										{
											echo '<option value="'.$hora.'">'.$hora.'</option>';
										}
										echo '</select>';
								echo '</div>';
							echo '</div>';
						echo '</div>';						
					echo '</div>';
					echo '<div class="form-group">';
						echo '<label for="asesor">¿Desea que lo visite un asesor?</label>';
						echo 'Si <input type="radio" name="asesor" value="si" > No <input type="radio" name="asesor" value="no" checked="checked">';
					echo '</div>';
					echo '<div class="form-group">';
						echo '<label for="name">Nombre y Apellido</label>';
						echo '<input type="text" name="nombre" id="name" class="form-control">';
					echo '</div>';
					echo '<div class="form-group">';
						echo '<label for="email">E-mail</label>';
						echo '<input type="text" name="email" id="email" class="form-control">';
					echo '</div>';
					echo '<div class="row">';
						echo '<div class="col-sm-6">';
							echo '<div class="form-group">';
								echo '<label for="tel">Teléfono</label>';
								echo '<input type="text" name="tel" id="tel" class="form-control">';
							echo '</div>';
						echo '</div>';
						echo '<div class="col-sm-6">';
							echo '<div class="form-group">';
								echo '<label for="cel">Celular</label>';
								echo '<input type="text" name="cel" id="cel" class="form-control">';
							echo '</div>';
						echo '</div>';
					echo '</div>';
					echo '<div class="form-group">';
						echo '<label for="address">Dirección</label>';
						echo '<input type="text" name="direccion" id="address" class="form-control">';
					echo '</div>';
					echo '<div class="form-group">';
						echo '<label for="message">Mensaje</label>';
						echo '<textarea name="mensaje" id="message" cols="30" rows="5" class="form-control"></textarea>';
					echo '</div>';
					echo '<div class="form-group">';
						echo '<label for="captcha">Escribe el código captcha</label>';
						echo '<div class="row">';
							echo '<div class="col-sm-4"><input type="text" name="captcha" id="captcha" class="form-control"></div>';
							echo '<div class="col-sm-4"><img src="'.$url.'funciones/enviar-email-2-mostrar-captcha.php" alt=""></div>';
						echo '</div>';
					echo '</div>';
					echo '<input type="hidden" name="origen" value="pedido">';
					echo '<button id="send" type="submit" name="enviar" class="btn btn-primary">Enviar</button>';
					echo '<div id="send-message"></div>';
				echo '</form>';
			echo '</div>';
			echo '<div class="col-md-4 hidden-sm hidden-xs">';			
				echo '<img src="'.$url.'imagenes/iconos/vista-contacto.jpg" alt="">';
			echo '</div>';
		echo '</div>';
	echo '</section>';
echo '</div>';

include $url.'funciones/mostrar-pie.php';

echo '</body>';
echo '</html>';

?>

<script>	
	$('#send').click(function(){
		$('#send').val('Enviando');
		$('#motivo, #servicio, #hora1, #hora2, #name, #email, #tel, #cel, #address, #message, #captcha').removeClass('alert-border-red');
		var error = false;
		$('#send-message').fadeOut(250);

		if($('#motivo').val() == 0){
			$('#motivo').addClass('alert-border-red'); error = true;
			$('#motivo').focus();
		}

		if($('#servicio').val() == 0){
			if(!error) $('#servicio').focus();
			$('#servicio').addClass('alert-border-red'); error = true;
		}

		if($('#name').val().trim() == ''){
			if(!error) $('#name').focus();
			$('#name').addClass('alert-border-red'); error = true;
		}

		if($('#email').val().trim() == '' || $('#email').val().indexOf('@') == -1 || $('#email').val().indexOf('.') == -1){
			if(!error) $('#email').focus();
			$('#email').addClass('alert-border-red'); error = true;
		}

		if($('#tel').val().trim() == '' || $('#tel').val().length < 6){
			if(!error) $('#tel').focus();
			$('#tel').addClass('alert-border-red'); error = true;
		}
		
		if($('#cel').val().trim() == '' || $('#cel').val().length < 6){
			if(!error) $('#cel').focus();
			$('#cel').addClass('alert-border-red'); error = true;
		}

		/*if($('#address').val().trim() == ''){
			if(!error) $('#address').focus();
			$('#address').addClass('alert-border-red'); error = true;
		}*/

		if($('#message').val().trim() == ''){
			if(!error) $('#message').focus();
			$('#message').addClass('alert-border-red'); error = true;
		}

		if($('#captcha').val().trim() == ''  || $('#captcha').val().length < 5){
			if(!error) $('#captcha').focus();
			$('#captcha').addClass('alert-border-red'); error = true;
		}
	});
}
</script>
