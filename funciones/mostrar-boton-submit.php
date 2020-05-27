<?php if (!isset($_SESSION)) {session_start();}

	echo '<center>';

		echo '<br/>';
		echo '<a class="salir" href="'.$salida_a.'?tabla_a_procesar='.$tabla_a_procesar.'&capitulo='.$capitulo.'">Salir</a>';
		echo '<br/>';

		if(!empty($accion))
		{	
			if($post === "")
			{
				// $boton_submit_deshabilitado = (isset($deshabilitar_boton_agregar) and $deshabilitar_boton_agregar and $accion == 'agregar') ? 'disabled' : '';
				$boton_submit_deshabilitado = '';
				echo '<input class="grabar" type="submit" name="grabar" value="'.ucwords($accion).'" '.$boton_submit_deshabilitado.'/>';
				if(isset($_GET['id']))
				{
					echo '<input type="hidden" name="id" value="'.$_GET['id'].'"/>';
				}

				echo '<input class="limpiar" type="reset" name="limpiar" value="Limpiar"/>';		
				
			}
			elseif($post === "si")
			{
				echo '<input class="continuar" type="submit" name="limpiar" value="Continuar"/>';
			}
		}	
	echo '</center>';
	echo '<br/>';
?>
