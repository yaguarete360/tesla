<?php if(!isset($_SESSION)) {session_start();}

$permitido = "no";
$esta_seccion = "";
if(!isset($_SESSION['alias_en_sesion'])) $_SESSION['alias_en_sesion'] = "";

if(isset($_GET['solapa'])) //armador para mostrar-menu-contenido.php && listar-vinculos.php
{
	if($_GET['solapa'] == "datos")
	{
		$esta_seccion = $_GET['solapa'];
	}
	elseif($_GET['solapa'] == "procesos" or $_GET['solapa'] == "reportes" or $_GET['solapa'] == "sintesis")
	{
		$esta_seccion = $_GET['solapa']."-".$_GET['categoria'];
	}
}
elseif(isset($_GET['tabla_a_procesar'])) //armador para los php formular-...php
{
	if(isset($_GET['capitulo']))
	{
		$agregados_esta_seccion_formular = $_GET['capitulo'];
	}
	elseif(isset($_GET['caso']) or isset($_GET['id']))
	{
		$switch_get_caso_permisos = (isset($_GET['caso'])) ? $_GET['caso'] : 'id';
		switch ($switch_get_caso_permisos)
		{
			case 'agregar':
				$agregados_esta_seccion_formular = 'altas';
			break;
			case 'id':
				$agregados_esta_seccion_formular = '';
			break;
		}
	}
	$esta_seccion = "datos-".$_GET['tabla_a_procesar']."-".$agregados_esta_seccion_formular;
}
elseif(isset($capitulo)) //armador procesos y reportes
{
	$esta_seccion = $capitulo."-".str_replace(".php", "", $esta_vista);
}

if((empty($esta_seccion) or $_SESSION['alias_en_sesion'] == "admin") or (isset($_SESSION['alias_en_sesion']) and $esta_seccion == "procesos-modificar-mi-perfil"))
{
	$permitido = "si";
}
else
{
	include $url.'funciones/conectar-base-de-datos.php';

	$esta_seccion_partes = explode("-", $esta_seccion);
	($esta_seccion_partes[0] == "datos" and isset($esta_seccion_partes[2]) and $esta_seccion_partes[2] != "bajas") ? $esta_seccion_general = $esta_seccion_partes[0]."-".$esta_seccion_partes[1]: $esta_seccion_general = $esta_seccion;
	
	$consulta_alias = ' SELECT *
	FROM permisos
	WHERE borrado LIKE "no"
	AND alias LIKE "'.$_SESSION['alias_en_sesion'].'"
	AND (permiso LIKE "'.$esta_seccion.'%"
	OR permiso LIKE "'.$esta_seccion_general.'")';

	$query_alias = $conexion->prepare($consulta_alias);
	$query_alias->execute();

	while($rows = $query_alias->fetch(PDO::FETCH_ASSOC)) $permitido = "si";
}

if($permitido == "no")
{
	echo '<div class="top-header"';
		echo 'style="background-image: url('.$url.'imagenes/iconos/cabecera.jpg)">';
		echo '<div class="container">';
			echo '<h1>Pagina No Encontrada</h1>';
		echo '</div>';
	echo '</div>';
	echo '<div class="container">';
		echo '<section class="interna">';
			echo '<div class="row">';
				echo '<div class="col-sm-12">';
					
					echo '<span style="color:#e60000;font-weight:900;">La seccion deseada no se encuentra disponible o no existe.</span>';
					echo '<br/>';
					echo '<span style="color:#e60000;font-weight:900;">Favor contactar con el administrador del sistema.</span>';
					echo '<br/>';

					include $url.'funciones/mostrar-login.php';

				echo '</div>';
			echo '</div>';
		echo '</section>';
	echo '</div>';
    
	include $url.'funciones/mostrar-pie.php';
	
	echo '</body>';
	echo '</html>';
	
	exit;
}

?>
