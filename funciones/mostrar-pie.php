<?php if (!isset($_SESSION)) {session_start();}

$titulo = "AVENIDA ESPAÑA";
$nombre = "Casa Central";
$direccion = "Av. España esquina Boquerón.";
$ciudad = "Asunción, Paraguay.";
$telefono = "595 21 207013";
$latitud = -25.285815968068402;				
$longitud = -57.617779076099396; 
$descripcion = '<div class="head-sucursal">
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent ultricies tellus vitae nunc rutrum, et rhoncus magna malesuada. Vestibulum semper aliquet lectus, non molestie est. Fusce ut tincidunt odio, eleifend lobortis nulla. Pellentesque nec sem diam.</p>
				</div>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam lacinia arcu et ipsum mollis, in viverra risus tempor. Sed gravida egestas interdum. Praesent suscipit massa sed mauris lacinia, ut sagittis justo gravida. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Interdum et malesuada fames ac ante ipsum primis in faucibus. Vivamus sollicitudin erat sed convallis sollicitudin. Vivamus nec urna eu risus rhoncus tempus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris a rutrum sapien, a sagittis quam. Pellentesque interdum nulla aliquet, aliquet diam vitae, lacinia erat. Sed molestie erat ac dignissim placerat.</p>
				<p>Cras aliquam, ante quis mattis luctus, dolor magna dictum eros, eget gravida nisi sem ac quam. Integer at velit ante. Curabitur ullamcorper lorem tincidunt velit posuere, in mollis est porttitor. Etiam feugiat diam enim, sed imperdiet urna auctor vel. Sed hendrerit elit mauris, non tristique purus mollis ut. Donec consectetur mauris quis risus viverra, nec ullamcorper leo bibendum. Cras eu pretium ipsum, sit amet dapibus ipsum. Aliquam rhoncus iaculis est, ac fringilla diam finibus sit amet. Phasellus ut velit sed est euismod vestibulum quis porttitor elit. Proin condimentum rhoncus ultricies. Suspendisse pulvinar aliquet magna sit amet sollicitudin.</p>
				<h4 class="title">Servicios</h4>
				<ul>
				<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
				<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
				<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
				<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
				<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
				</ul>';

echo '<footer>';

	echo '<div class="container">';
		echo '<a href="'.$url.'">';
		 	echo '<img class="logo-pie" src="'.$url.'imagenes/iconos/logo-pie.png" alt="Parque Serenidad">'; 
		echo '</a>';
		

		echo '<div class="datos-pie">';
			
			echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';			
			echo '<strong>';
				echo 'Parque Serenidad';
			echo '</strong>&nbsp&nbsp(c) 2016';
			echo '<br/>';
				echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
				echo $direccion;
			echo '<br/>';
			echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
			echo 'Teléfono 1: ';				
				echo $telefono;
			echo '<br/>';
			echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
			echo 'Teléfono 2: ';				
				echo '595 211 452';
			echo '<br/>';
			echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
			echo 'Teléfono 3: ';				
				echo '595 224 727';
			echo '<br/>';
			echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
			echo 'Teléfono 4: ';				
				echo '595 221 328/9';
			echo '<br/>';
				echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
				echo $ciudad;
			echo '<br/>';
			
		
		echo '</div>';
		
		echo '<img class="logo-dinapi" src="'.$url.'imagenes/iconos/logo-dinapi.png" alt="Dinapi"/>';
	
		echo '<br/>';
		echo '<br/>';
		echo '<br/>';
	
	echo '</div>';
echo '</footer>';

?>

<script>

    $(window).on('load', function(){
        $( ".div_cargando" ).fadeOut(500, function() {
            $( ".div_cargando" ).remove();
        });  
    });

</script>
