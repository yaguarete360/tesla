<?php if (!isset($_SESSION)) {session_start();}

$url = "../";

$retroceder="";
$hoy = date("Y-m-d");
$esta_vista = "Resultado del envío";

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
				echo '<h4 class="title">Formulario de envío envio de email de condolencias</h4>';
				
				include $url.'funciones/enviar-email-3-aprobar-captcha.php';
				
				if($captcha_autorizado == "si")
				{
					if(isset($_POST['enviar']))
					{						
						switch ($_POST['origen']) 
						{
							case 'condolencia':
								if(!empty($_POST['nombre']) and 
								   !empty($_POST['familiar']) and 
								   !empty($_POST['mensaje'])
								)
								{
									$mensaje = 'Condolencias para los familiares de '.$_POST['difunto'].'. ';
									$mensaje.= 'De parte de '.$_POST['nombre'].'. ';
									$mensaje.= 'Favor entregar este mensaje en especial a '.$_POST['familiar'].'. ';
									$mensaje.= 'MENSAJE: '.$_POST['mensaje'].'. ';
									mail("servicios@parqueserenidad.com", "", $mensaje, $_POST['email']);
									mail("fernando@parqueserenidad.com", "", $mensaje, $_POST['email']);
									mail("enrique@parqueserenidad.com", "", $mensaje, $_POST['email']);
									echo '<p>Su email de condolencias ha sido enviado exitosamente.</p>';
								}
								else
								{
									echo '<p><b>No pudimos enviar su email pues la informacion no esta completa.<br/>';
									echo 'Favor complete correctamente el formulario y envielo nuevamente</b></p>';
								}
							break;
							case 'pedido': case 'contacto':
								if(!empty($_POST['nombre']) and 
								   !empty($_POST['cel']) and 
								   !empty($_POST['email']) and 
								   !empty($_POST['direccion']) and 
								   !empty($_POST['mensaje'])
								)
								{
									$mensaje = 'Mensaje de '.$_POST['motivo'].'<br>';
									$mensaje = 'Con relacion a '.$_POST['servicio'].'<br>';
									$mensaje.= 'Del Cliente '.$_POST['nombre'].'<br>';
									$mensaje.= 'Con el email '.$_POST['email'].'<br>';
									$mensaje.= 'Disponible de '.$_POST['dia_1'].'<br>';
									$mensaje.= ' a '.$_POST['dia_2'].'<br>';
									$mensaje.= 'De '.$_POST['hora_1'].'<br>';
									$mensaje.= ' a '.$_POST['hora_2'].'<br>';
									$mensaje.= 'Telefono '.$_POST['tel'].'<br>';
									$mensaje.= 'Celular '.$_POST['cel'].'<br>';
									$mensaje.= 'Direccion '.$_POST['direccion'].'<br>';
									$mensaje.= 'Solicita asesor? '.$_POST['asesor'].'<br>';
									$mensaje.= 'MENSAJE: '.$_POST['mensaje'].'<br>';
									/*mail("info@parqueserenidad.com", "", $mensaje, $_POST['email']);	
									mail("servicios@parqueserenidad.com", "", $mensaje, $_POST['email']);
									mail("fernando@parqueserenidad.com", "", $mensaje, $_POST['email']);
									mail("enrique@parqueserenidad.com", "", $mensaje, $_POST['email']);
									mail("victor@parqueserenidad.com", "", $mensaje, $_POST['email']);
									mail("administracion@parqueserenidad.com", "", $mensaje, $_POST['email']);*/
									$mensaje = $mensaje;
									$titulo = "[ENVIO DE CORREO DESDE LA WEB - FECHA: $hoy]";
									$enviar_a = 
									array(
										'info@parqueserenidad.com',
										'servicios@parqueserenidad.com',
										'enrique@parqueserenidad.com',
										'victor@parqueserenidad.com',
										'administracion@parqueserenidad.com',
										'fernando@parqueserenidad.com'

									);
									include "../librerias/correo/enviar.php";
									echo '<p>Su email de condolencias ha sido enviado exitosamente.</p>';
								}
								else
								{
									echo '<p><b>No pudimos enviar su email pues la informacion no esta completa.<br/>';
									echo 'Favor complete correctamente el formulario y envielo nuevamente</b></p>';
								}
							break;
						}
					}
				}
				else
				{
					echo '<p><b>No pudimos enviar su email pues su identificacion no es la correcta.<br/>';
					echo 'Favor complete correctamente el formulario y envielo nuevamente</b></p>';
				}
			echo '</div>';
		echo '</div>';
	echo '</section>';
echo '</div>';

include $url.'funciones/mostrar-pie.php';

echo '</body>';
echo '</html>';

?>
