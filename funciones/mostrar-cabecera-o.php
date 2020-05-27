<?php if (!isset($_SESSION)) {session_start();}

header('Content-Type: text/html; charset=utf-8');
//echo "EL SISTEMA ESTARA FUERA DE SERVICIO POR 20 MIN.";die();
$meta_title = "Parque Serenidad";

echo '<!DOCTYPE html>';
echo '<html lang="es">';

echo '<head>';
	if(isset($titulo))
	{
		echo '<title>'.$titulo.' | '.$meta_title.'</title>';		
	}
	else
	{
		echo '<title>'.$meta_title.'</title>';		
	}

	include $url.'funciones/cargar-links.php';

	include $url.'funciones/conectar-base-de-datos.php';

	include $url.'funciones/guardar-url-ingresado.php';
	
echo '</head>';

echo '<body>';
    echo '<div class="div_cargando"></div>';
	date_default_timezone_set('America/Asuncion');
	
	// include $url.'funciones/mensajear.php';

	echo '<header>';
		echo '<div class="container">';
			echo '<nav class="navbar navbar-default">';
				echo '<div class="container-fluid">';
					echo '<div class="navbar-header">';
						echo '<a href="'.$url.'" class="logo">Parque Serenidad</a>';
						echo '<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navigation" aria-expanded="false">';
							echo '<span class="sr-only">Menu</span>';
							echo '<span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>';
						echo '</button>';
						echo '<button type="button" class="navbar-search visible-xs-block" data-toggle="collapse" data-parent="#toptabs" href="#mainsearch" aria-expanded="true" aria-controls="toptabs">';
							echo '<span class="sr-only">Buscar</span>';
							echo '<span class="glyphicon glyphicon-search" aria-hidden="true"></span>';
						echo '</button>';
					echo '</div>';
                    
                    if(!isset($no_mostrar_menu) or (isset($_SESSION['usuario_en_sesion']) and $_SESSION['alias_en_sesion'] == "admin"))
					{
						include $url.'funciones/mostrar-menu.php';
					}
					
				echo '</div>';
			echo '</nav>';

            if(isset($_SESSION['usuario_en_sesion']) and (!isset($no_mostrar_menu) or $_SESSION['alias_en_sesion'] == "admin"))
			{
				echo '<a href="'.$url.'funciones/modificar-mi-perfil.php">';
					echo '<span style="position:relative;bottom:0px;color:#58453D;">';
						echo "Hola, ".ucwords($_SESSION['usuario_en_sesion']);
					echo '</span>';
				echo '</a>';
			}
		echo '</div>';
		echo '<div class="panel-group" id="toptabs">';
		    echo '<div class="panel panel-default">';
		        echo '<div class="panel-collapse collapse" id="mainsearch">';
		            echo '<div class="panel-body">';
		                echo '<div class="panel-centered">';
		                    echo '<div class="container">';
		                    	echo '<form id="form-search" action="'.$url.'funciones/buscar-contenido.php" method="get">';
			                    	echo '<div class="row">';
			                    		echo '<div class="col-sm-2">';
			                    		echo '</div>';
				                    	echo '<div class="col-sm-6">';
				                    		echo '<input class="searchfield" id="q" name="q" placeholder="" type="text" value="';
				                    		
				                    		if(isset($_GET['q']))
				                    		{
				                    			echo $_GET['q'];
				                    		} 
				                    		
				                    		echo '">';
				                    	echo '</div>';
				                    	echo '<div class="col-sm-2">';
				                    		echo '<div class="btn btn-primary btn-block" id="btn-main-search" type="submit">';
						                    	echo 'Buscar';
						                    echo '</div>';
				                    	echo '</div>';
			                    		echo '<div class="col-sm-2">';
			                    		echo '</div>';
				                    echo '</div>';
			                    echo '</form>';
		                    echo '</div>';
		                echo '</div>';
		            echo '</div>';
		        echo '</div>';
		    echo '</div>';
		echo '</div>';
	echo '</header>';
	include $url.'funciones/controlar-permisos.php';
	if(isset($_SESSION["usuario_en_sesion"])){
		include $url.'funciones/buscar-interno.php';
	}
	
?>

<script>

    $(window).on('load', function(){
        $( ".div_cargando" ).fadeOut(500, function() {
            $( ".div_cargando" ).remove();
        });  
    });

</script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-122181959-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-122181959-1');
</script>

