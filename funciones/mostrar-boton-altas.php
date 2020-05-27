<?php if (!isset($_SESSION)) {session_start();}					

echo '<div class="icono-agregar">';

	$subtitulo = $titulo;
		
	echo '<div class="subtitulo">';
		echo '<a href="../funciones/formular-altas.php?tabla_a_procesar='.$tabla_a_procesar;
		echo '&caso=agregar"><span class="verde">Agregar más '.strtoupper($subtitulo).'</span></a>';
	echo '</div>';
	
echo '</div>';
?>
