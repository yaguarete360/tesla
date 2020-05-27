<?php if (!isset($_SESSION)) {session_start();}

$esta_vista = basename(__FILE__,'.php');

$url = "../";
include "../funciones/mostrar-cabecera.php";

echo '<link rel="stylesheet" href="'.$url.'css/toastr.css">';
echo '<link rel="stylesheet" href="'.$url.'css/sweetalert.css">';

echo '<script src="'.$url.'librerias/js/sweetalert.js" type="text/javascript"></script>';
echo '<script src="'.$url.'librerias/js/toastr.min.js" type="text/javascript"></script>';

$titulo = "ACTUALIZACION DE DATOS";
$descripcion = '
En <b>PARQUE SERENIDAD</b> ponemos a su disposición profesionales calificados 
y especialmente entrenados para acompañar a las familias en duelo y asesorarlas acertadamente en lo que fuera necesario en tan difícil momento.
<br/>
Para ello, constituímos un equipo de personas preparadas profesionalmente para
asistir, aconsejar y proveer las mejores soluciones segun cada necesidad en particular.
<br/>
Nuestras premisas se sustentan en:
<ul> 
	<li> El bienestar de nuestra gente y sus familias es el fruto de lo bien hecho. </li>
	<li> El permanente entrenamiento profesional aumenta la calidez y la calidad. </li>
	<li> La antiguedad del personal es la experiencia fundamental para toda buena labor. </li>
	<li> La tranquilidad de la estabilidad laboral es un valor transferido al cliente. </li>
	<li> Los buenos resultados son aquellos que alcanzan a todo el equipo. </li>
</ul>
';

echo '<div class="top-header"';
	echo 'style="background-image: url(../imagenes/iconos/cabecera.jpg)">';
	echo '<div class="container">';
		echo '<h1>'.$titulo.'</h1>';
	echo '</div>';
echo '</div>';
echo '<div class="container">';
	echo '<section class="interna">';
		echo '<div class="row">';
			echo '<div class="col-sm-6">';
			 echo '<label for="text">VALIDO SOLO PARA CLIENTES!</label><br>';
			// echo '<div class="obligatario">(*) Campos Obligatorios.</div>';
			 echo '<br>';
			echo '<form id="form1" method="post" action="../funciones/guardar-datos-actualizacion.php" onsubmit="return validar()">';
				  echo '<div class="form-group">';
				  //echo '<div class="obligatario">(*)</div>';
				    echo '<label for="text">Nombre y Apellido:</label>';
				    echo '<input type="text" class="form-control pri" id="nombre" name="nombre">';
				  echo '</div>';
				  echo '<div class="form-group">';
				  //echo '<div class="obligatario">(*)</div>';
				    echo '<label for="text">Donde te encontramos(Teléfono o Celular):</label>';
				    echo '<input type="text" class="form-control" id="contacto" name="contacto">';
				  echo '</div>';

				   echo '<div class="form-group">';
				  // echo '<div class="obligatario">(*)</div>';
				    echo '<label for="text">Observaciones:</label>';
				    echo '<input type="text" class="form-control" id="obs" name="obs">';
				   echo '</div>';

				  /* echo '<div class="form-group">';
				   //echo '<div class="obligatario">(*)</div>';
				    echo '<label for="text">Primer Apellido:</label>';
				    echo '<input type="text" class="form-control" id="primera" name="primera">';
				  echo '</div>';
				   echo '<div class="form-group">';
				   //echo '<div class="obligatario">(*)</div>';
				    echo '<label for="text">Segundo Apellido:</label>';
				    echo '<input type="text" class="form-control" id="segundoa" name="segundoa">';
				  echo '</div>';

				   echo '<div class="form-group">';
				   echo '<br>';
				    echo '<label for="text">Tercer Apellido:</label>';
				    echo '<input type="text" class="form-control" id="tercera" name="tercera">';
				  echo '</div>';

				    echo '<div class="form-group">';
				    //echo '<div class="obligatario">(*)</div>';
				    echo '<label for="text">Nro Cedula:</label>';
				    echo '<input type="text" class="form-control" id="cedula" name="cedula">';
				  echo '</div>';

				   echo '<div class="form-group">';
				   //echo '<div class="obligatario">(*)</div>';
				    echo '<label for="text">Direccion:</label>';
				    echo '<input type="text" class="form-control" id="direccion" name="direccion">';
				  echo '</div>';

				   echo '<div class="form-group">';
				   //echo '<div class="obligatario">(*)</div>';
				    echo '<label for="text">Telefono:</label>';
				    echo '<input type="text" class="form-control" id="telefono" name="telefono">';
				  echo '</div>';

				   echo '<div class="form-group">';
				    echo '<br>';
				    echo '<label for="email">Email:</label>';
				    echo '<input type="email" class="form-control" id="email" name="email" requerid>';
				  echo '</div>';
				 */
				  echo '<button type="submit" class="btn btn-default">Enviar</button>';
			echo '</form>';

				
		echo '</div>';
	echo '</section>';
echo '</div>';

include "../funciones/mostrar-pie.php";

echo '</body>';
echo '</html>';

/*******************PREMIOS********************/
echo '<div class="modal fade mymodal" id="premios" tabindex="-1" role="dialog" aria-labelledby="premios" aria-hidden="true">';
    echo '<div class="modal-dialog">';
        echo '<div class="modal-content">';
            echo '<div class="modal-header">';
            echo '<div class="icono">';
              echo '<IMG SRC="../imagenes/iconos/logo-pdf.png" WIDTH=300 HEIGHT=180 ALT="Obra de K. Haring">';
            echo '</div>';  
            echo '</div>';
            echo '<div class="modal-body">';
               echo '<div id="msg"><strong>1 - Premio A</strong><br>
               <strong>2 - Premio B</strong><br>
               <strong>3 - Premio C</strong></div>';
            echo '</div>';
            echo '<div class="modal-footer">';
             echo '<form action="funciones/actualizar-datos.php" method="POST">';
                echo '<button type="button" class="btn btn-primary" data-dismiss="modal">Salir</button>&nbsp&nbsp';
                echo '<input type="hidden" name="myValue" id="myValue" value=""/>';
               // echo '<button class="btn btn-primary" data-title="Actualizar">Actualizar Datos</button>';
                echo '</form>';
            echo '</div>';
        echo '</div>';
    echo '</div>';
echo '</div>';

?>

<script type="text/javascript">

function validar(){
	
	if($("#nombre").val()==""){
		swal("Aviso!", "Debes ingresar tu primer Nombre y Apellido.", "error");
		$("#nombre").focus();
		return false;
	}

	if($("#contacto").val()==""){
		swal("Aviso!", "Debes ingresar tu Nro de Telefono o correo electronico.", "error");
		$("#contacto").focus();
		return false;
	}

	return true;
}


</script>

<style type="text/css">

	.obligatario{
		color: red;
	}

	.icono{
		margin-left: 24%;
	}
</style>
