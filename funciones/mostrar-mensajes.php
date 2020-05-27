<?php if (!isset($_SESSION)) {session_start();}

include $url.'pse-red/funciones/conectar-base-de-datos.php';

$consulta_ver_mensajes = 'SELECT *
FROM operaciones_mensajes
WHERE borrado = "0000-00-00"
AND (usuario_destino = "'.$_SESSION['alias_en_sesion'].'" OR usuario_destino = "Todos")
ORDER BY fecha
ASC';

try
{
	$query_vm = $conexion->prepare($consulta_ver_mensajes);
	$query_vm->execute();
}
catch(PDOException $e)
{
    echo '<div>';
		echo "Ha ocurrido un error. Favor intente de nuevo.";
	echo '</div>';
}

$i_mensaje = 0;

while($rows = $query_vm->fetch(PDO::FETCH_ASSOC))
{
	$fechas[$i_mensaje] = $rows['fecha'];
	$menu_nombresajes[$i_mensaje] = $rows['mensaje'];
	$origenes[$i_mensaje] = $rows['usuario_origen'];
	$i_mensaje++;
}

echo '<div class="mensajes-inicio">';
	echo '<table>';
		echo '<tr>';
			echo '<th style="font-size:25px;">';
				echo "Mensajes";
			echo '</th>';
		echo '</tr>';
		echo '<tr>';
			echo '<th style="font-size:15px;">';
				echo "Fecha&nbsp&nbsp&nbsp";
			echo '</th>';
			echo '<th style="font-size:15px;">';
				echo "Remitente&nbsp&nbsp&nbsp";
			echo '</th>';
			echo '<th style="font-size:15px;">';
				echo "Mensaje&nbsp&nbsp&nbsp";
			echo '</th>';
		echo '</tr>';
		if($i_mensaje == 0)
		{
			echo '<tr>';
				echo '<td colspan="3">';
					echo "Ningun mensaje que mostrar.&nbsp&nbsp&nbsp";
				echo '</td>';
			echo '</tr>';
		}
		else
		{
			foreach ($menu_nombresajes as $i_mensaje_nombre => $menu_nombresaje)
			{
				echo '<tr>';
					echo '<td>';
						echo $fechas[$i_mensaje_nombre]."&nbsp&nbsp&nbsp";
					echo '</td>';
					echo '<td>';
						echo $origenes[$i_mensaje_nombre]."&nbsp&nbsp&nbsp";
					echo '</td>';
					echo '<td>';
						echo (strlen($menu_nombresaje) > 50) ? substr($menu_nombresaje, 0, 50)."..." : $menu_nombresaje."&nbsp&nbsp&nbsp";
					echo '</td>';
				echo '</tr>';
				if($i_mensaje_nombre == "4") break;
			}
					echo '<tr>';
						echo '<td colspan="3">';
							$difMens = $i_mensaje - ($i_mensaje_nombre + 1);
							echo '<i><a href="'.$url.'pse-red/vistas/procesos/ver-mensajes.php">Ver todos (+'.$difMens.')</a></i>';
						echo '</td>';
					echo '</tr>';
		}
	echo '</table>';
echo '</div>';
?>