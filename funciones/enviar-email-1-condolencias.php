<?php if (!isset($_SESSION)) {session_start();}

$url = "../../";

if(isset($_GET['url'])) $url = $_GET['url'];

$desde_ano = date('Y', strtotime('-1 years'));
$hasta_ano = date('Y');

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
			echo '<div class="col-sm-12">';
				echo '<br/>';
				if(!isset($_GET['difunto_del_listado']) and !isset($_GET['difunto_cargado']))
				{
					if(!isset($_GET['difunto_seleccionado']))
					{
						echo '<h4>';
							echo 'Sepelios del año '.$hasta_ano.' y '.$desde_ano.' por fecha.</h4>';								
						echo '</h4>';
						echo 'Favor haga click sobre el nombre.';								
						echo '</br>';
						echo '</br>';
						
						include $url.'funciones/conectar-base-de-datos.php';
						
						include $url.'funciones/obtener-sepelios-alfabetico.php';
						
						echo '<div class="condolencias">';
							foreach($casos as $ind => $caso)
							{
								echo '<b><a href="'.$url.'vistas/sepelios/enviar-condolencias.php?difunto_seleccionado='.$casos[$ind]['difunto'].'">';
					        		echo '<span class="condolencias-fecha">'.$casos[$ind]['fecha'].'</span>';
					        		echo '<span class="condolencias-nombre"> + '.$casos[$ind]['difunto'];
					    		echo '</a></b>';
						    	echo '</br>';
					  		};
					  	echo '</div>';
					  	echo '</br>';
						echo 'Si no encontró, favor ingrese el nombre del difunto en el cuadro de abajo';
				  		echo '<a href="'.$url.'vistas/sepelios/consultar-exequias.php"> o haga click aquí para <b>buscar más + </b>.</a>';									
						echo '<form>';				
							echo '<br/>';
							echo '<input type="text" name="difunto_cargado" value="" size="80"/>';	
						echo '<br/>';
						echo '<input type="submit" name="continuar" value="Continuar">';
					echo '</form>';					  	
					}

				}				
				
				$difunto = "";
				
				if(!empty($_GET['difunto_seleccionado'])) $difunto = $_GET['difunto_seleccionado'];
				if(isset($_GET['difunto_cargado'])) $difunto = $_GET['difunto_cargado'];
				if(isset($_GET['difunto_del_listado'])) $difunto = $_GET['difunto_del_listado'];
				if(isset($difunto) and !empty($difunto))
				{
					echo 'A los deudos de quien en vida fuera';
					echo '<br/>';
					echo '<h4>'.$difunto.'</h4>';
					echo '<form action="'.$url.'funciones/enviar-email-4-enviar.php" method="post"/>';
						echo '<input type="hidden" name="difunto" value="'.$difunto.'">';
						echo '<div class="form-group">';
							echo '<label for="name">Nombre y Apellido</label>';
							echo '<input type="text" name="nombre" id="name" class="form-control">';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label for="email">E-mail</label>';
							echo '<input type="text" name="email" id="email" class="form-control">';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label for="email">Familiar o Persona Cercana a quien va dirigida la condolencia</label>';
							echo '<input type="text" name="familiar" id="family" class="form-control">';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label for="message">Mensaje de condolencia</label>';
							echo '<textarea name="mensaje" id="message" cols="30" rows="5" class="form-control"></textarea>';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label for="captcha">Escribe el código captcha</label>';
							echo '<div class="row">';
								echo '<div class="col-sm-4"><input type="text" name="captcha" id="captcha" class="form-control"></div>';
								echo '<div class="col-sm-4"><img src="'.$url.'funciones/enviar-email-2-mostrar-captcha.php" alt=""></div>';
							echo '</div>';
						echo '</div>';
						echo '<input type="hidden" name="origen" value="condolencia">';
						echo '<input id="btn-send-condolencias" type="submit" name="enviar" value="Enviar">';
					echo '</form>';
				}
				if(isset($_GET['difunto_seleccionado']) and empty($_GET['difunto_seleccionado']))
				{
					echo "Favore escriba el nombre del difunto a cuya familia desea enviar sus condolencias";
				} 
				if(isset($_GET['difunto_cargado']) and empty($_GET['difunto_cargado']))
				{
					echo "Favor intente de nuevo pues no definió el nombre del difunto a cuya familia desea enviar sus condolencias";
				} 
			echo '</div>';
		echo '</div>';
	echo '</section>';
echo '</div>';

include $url.'funciones/mostrar-pie.php';

echo '</body>';
echo '</html>';

?>

<script>
	$(function() 
	{
		$('#select-difundo').change(function()
		{
			if($(this).val() == 0){
				$('#difunto').prop('disabled', false);
			}else{
				$('#difunto').prop('disabled', true);
			}
		});
		$('#btn-send-condolencias').click(function()
		{
			
			$('#btn-send-condolencias').val('Enviando');
			
			$('#difunto, #name, #email, #family, #message, #captcha').removeClass('alert-border-red');
			var error = false;
			
			$('#send-message').fadeOut(250);

			if($('#select-difundo').val() == 0 && $('#difunto').val().trim() == '')
			{
				$('#difunto').addClass('alert-border-red'); error = true;
				$('#difunto').focus();
			}

			if($('#name').val().trim() == '')
			{
				if(!error) $('#name').focus();
				$('#name').addClass('alert-border-red'); error = true;
			}

			if($('#email').val().trim() == '' || $('#email').val().indexOf('@') == -1 || $('#email').val().indexOf('.') == -1)
			{
				if(!error) $('#email').focus();
				$('#email').addClass('alert-border-red'); error = true;
			}

			if($('#family').val().trim() == '')
			{
				if(!error) $('#family').focus();
				$('#family').addClass('alert-border-red'); error = true;
			}

			if($('#message').val().trim() == '')
			{
				if(!error) $('#message').focus();
				$('#message').addClass('alert-border-red'); error = true;
			}

			if($('#captcha').val().trim() == ''  || $('#captcha').val().length < 5)
			{
				if(!error) $('#captcha').focus();
				$('#captcha').addClass('alert-border-red'); error = true;
			}
		});
	}
</script>
