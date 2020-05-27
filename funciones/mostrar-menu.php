<?php if (!isset($_SESSION)) {session_start();}

if(isset($_SESSION['alias_en_sesion']) and !empty($_SESSION['alias_en_sesion']))
{
	$tipo_menu = "red";
	$solapas = array('datos','procesos','reportes','sintesis');

	$permisos = array();

	$consulta = 'SELECT * 
		FROM permisos
		WHERE alias LIKE "'.$_SESSION['alias_en_sesion'].'"
		AND borrado = "no"
		ORDER BY id';

	$query = $conexion->prepare($consulta);
	$query->execute();
	
	while($rows = $query->fetch(PDO::FETCH_ASSOC))
	{
		$partes_del_permiso = explode("-", $rows['permiso']);
		$permisos[$partes_del_permiso[0]][$partes_del_permiso[1]] = "si";
	}
}
else
{
	$tipo_menu = "web";
	$solapas = array('quienes-somos','sepelios','servicios','sucursales');
}

echo '<div class="collapse navbar-collapse" id="main-navigation">';

	$menus = scandir($url.'vistas/', SCANDIR_SORT_ASCENDING);

	foreach($menus as $men=>$menu)
	{
		if($menu != '.' and 
		$menu != '..' and 
		$menu != '.DS_Store' and
		$menu != 'error_log' and
		in_array($menu, $solapas))
		{
			$menu_a_procesar[$menu] = scandir($url.'vistas/'.$menu,SCANDIR_SORT_ASCENDING);
		}
	}

	foreach($menu_a_procesar as $menu=>$submenu)
	{
		foreach ($submenu as $submenu_pos => $submenu_nombre)
		{
			if($submenu_nombre != '.' and 
				$submenu_nombre != '..' and 
				$submenu_nombre != '.DS_Store' and
				$submenu_nombre != 'error_log')
			{
				if($tipo_menu == "red")
				{
					$grupo = explode("-", str_replace(".php", "", $submenu_nombre));
					if($_SESSION['alias_en_sesion'] == "admin")
					{
						$menu_final[$menu][$grupo[0]] = "si";
					}
					else
					{
						if(isset($permisos[$menu][$grupo[0]])) $menu_final[$menu][$grupo[0]] = "si";
					}
				}
				else
				{
					$menu_final[$menu][str_replace(".php", "", $submenu_nombre)] = "si";
				}
			}
		}
	}

	echo '<ul class="nav navbar-nav">';
		foreach ($menu_final as $menu => $submenus)
		{
			echo '<li class="dropdown">';
				
				($menu == "datos") ? $es_datos = 'href="'.$url.'funciones/mostrar-menu-contenido.php?solapa='.$menu.'"' : $es_datos = 'href="#" data-toggle="dropdown"';
				
				echo '<a '.$es_datos.' class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">';
					echo str_replace("-", " ", $menu).'<span class="caret"></span>';
				echo '</a>';
				if($menu != "datos")
				{
					echo '<ul class="dropdown-menu">';
						foreach ($submenus as $submenu_nombre => $subsubmenus)
						{
							echo '<li>';
								if($tipo_menu == "web")
								{
									echo '<a href="'.$url.'vistas/'.$menu.'/'.$submenu_nombre.'.php">';
								}
								else
								{
									echo '<a href="'.$url.'funciones/mostrar-menu-contenido.php?solapa='.$menu.'&categoria='.$submenu_nombre.'">';
								}
									echo ucwords(str_replace("-", " ", $submenu_nombre));
								echo '</a>';
							echo '</li>';
						}
					echo '</ul>';
				}
			echo '</li>';
		}

		echo '<li>';
			echo '<a href="'.$url.'funciones/enviar-email-1-contacto.php">Contacto</a>';
		echo '</li>';
		echo '<li>';
			echo '<a class="navbar-search hidden-xs" data-toggle="collapse" data-parent="#toptabs"';
				echo 'href="#mainsearch" aria-expanded="true" aria-controls="toptabs">';
				echo '<span class="sr-only">';
				echo 'Buscar';
				echo' </span> <span class="glyphicon glyphicon-search" aria-hidden="true"></span>';
			echo '</a>';
		echo '</li>';

		if(isset($_SESSION['alias_en_sesion']) and !empty($_SESSION['alias_en_sesion']))
		{
			echo '<li>';
				echo '<a href="'.$url.'funciones/mostrar-logout.php">';
					echo '<span class="copyright">Logout</span>';
				echo '</a>';
			echo '</li>';
		}

	echo '</ul>';

echo '</div>';

echo '<div id="posicion_de_scroll"></div>';

?>

<script>
	$(function() {
		$('#btn-main-search').click(function(){
			$('#q').removeClass('alert-border-red');
			var error = false;
			
			if($('#q').val().trim() == ''){
				if(!error) $('#q').focus();
				$('#q').addClass('alert-border-red'); error = true;
			}

			if(!error){
				$('#form-search').submit();
				return true;
			}
			return false;
		});
	});
	
	$(window).scroll(function(){
		altura_total = $('body').height() - $(window).height();
		esta_posicion_scroll = $('#posicion_de_scroll').offset()['top'];
		porcentaje = ((esta_posicion_scroll / altura_total) * 100)+'%';
		$('#posicion_de_scroll').css('width', porcentaje);
	});
	
</script>
