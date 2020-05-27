<?php if (!isset($_SESSION)) {session_start();}

if(isset($_POST['solicitar']))
{
	$consulta_insertar_telemarketing = 'INSERT INTO telemarketing ('.implode(', ', array_keys($_POST['solicitud_telemarketing'])).') VALUES ("'.implode('", "', $_POST['solicitud_telemarketing']).'")';
	echo $consulta_insertar_telemarketing;
	$query_insertar_telemarketing = $conexion->prepare($consulta_insertar_telemarketing);
	$query_insertar_telemarketing->execute();
	echo '<h5>Usted ha solicitado más informacion sobre '.ucwords($motivo).'. Un asesor se comunicará con usted en la brevedad posible.</h5>';
}
else
{
	echo '<form method="post" action="">';
		echo '<input type="hidden" name="solicitud_telemarketing[motivo]" value="'.$motivo.'">';
		echo '<input type="hidden" name="solicitud_telemarketing[encargado]" value="pendiente">';
		echo '<table style="max-width:300px;">';
			echo '<tr>';
				echo '<td colspan="2">';
					echo '<b>Formulario para solicitar más información sobre '.ucwords($motivo).'</b>';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>';
					echo 'Nombre Completo *';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" name="solicitud_telemarketing[contacto_nombre]" value="" required>';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>';
					echo 'Correo Electronico *';
				echo '</td>';
				echo '<td>';
					echo '<input type="email" name="solicitud_telemarketing[contacto_mail]" value="" required>';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>';
					echo 'Numero de Telefono *';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" name="solicitud_telemarketing[contacto_telefono]" value="" required>';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>';
					echo 'Mensaje';
				echo '</td>';
				echo '<td>';
					echo '<textarea name="solicitud_telemarketing[telemarketing]" maxlength="240">';
	                echo '</textarea>';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td colspan="2">';
					echo '<i>* Campos Obligatorios</i>';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td colspan="2">';
					echo '<input type="submit" class="submit_aprobar" name="solicitar" value="Solicitar">';
				echo '</td>';
			echo '</tr>';
		echo '</table>';
	echo '</form>';
}

?>
