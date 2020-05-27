<?php 
if(!isset($_SESSION)){session_start();}

$nombre = $_POST['nombre'];
$contacto = $_POST['contacto'];
$obs = $_POST['obs'];

$hoy = date("Y-m-d H:i:s");

if(!empty($nombre) && !empty($contacto))
	{
		  	try
		  	 {
                include '../funciones/conectar-base-de-datos.php';

                /*if(empty($email)){
					$consulta = "SELECT email FROM actualizaciones WHERE cedula='".$cedula."'";
                }else{
                	$consulta = "SELECT email FROM actualizaciones WHERE email='".$email."'";
                }
				*/
                
     
        		/*$query_resultados = $conexion->prepare($consulta);
                $query_resultados->execute();
                $resultado = $query_resultados->fetchAll();
				*/

              /*  if(count($resultado)>0){
                   
				   $mensaje = "<strong>Ya has actualizado los datos!!!</strong>";
			       $mensaje = str_replace(" ", "-", $mensaje);
			       header("Location: ../funciones/mostrar-datos-actualizacion-mensaje.php?mensaje=".$mensaje);

				}else{
			*/	
					$sql = "INSERT INTO actualizaciones(id,
					actualizacion,
		            nombre,
		            contacto,
		            observacion,
		            creado) VALUES (
		            0, 
		            '".$hoy."',
		            '".$nombre."',
		            '".$contacto."',
		            '".$obs."',
		            '".$hoy."'
		            )";
		                                           
			   $stmt = $conexion->prepare($sql);
		       $stmt->execute(); 
		       
		       //$cupon = '<a href="parqueserenidad.com/funciones/mostrar-cupon.php?uuid='.$uuid.'">cupon</a>';
		       //$mensaje = '<strong>¿Actualizaste tus datos?</strong><br><br>Si actualizaste tus datos para '.$email.', estas participando del sorteo de importantes premios, haz clic en el link que aparece a continuación para ver su cupon. Si no solicitaste esto, ignora este correo electrónico.'.' '.$cupon;
		      
		       //mail($email,"CUPON PARQUE SERENIDAD S.R.L",$mensaje,"Content-type: text/html; charset=iso-8859-1");	
		       //echo 'correcto';
		       $mensaje = "<strong>Gracias Sr./Sra. ".$nombre."!! Los Datos fueron enviados correctamente!! En Breve nos pondremos en contacto con usted.</strong>";
		       //$mensaje = str_replace(" ", "-", $mensaje);
		       header("Location: ../funciones/mostrar-datos-actualizacion-mensaje.php?mensaje=".$mensaje);

			//	}
				
            }
			catch( PDOException $Exception )
			{
				$mensaje = "<strong>Error al enviar mensaje!!!</strong>";
				$mensaje = str_replace(" ", "-", $mensaje);
		       header("Location: ../funciones/mostrar-datos-actualizacion-mensaje.php?mensaje=".$mensaje);
			}

		
	}else
	{
		$mensaje = "<strong>Debes completar correctamente los datos!!!</strong>";
		$mensaje = str_replace(" ", "-", $mensaje);
		       header("Location: ../funciones/mostrar-datos-actualizacion-mensaje.php?mensaje=".$mensaje);
	}


?>