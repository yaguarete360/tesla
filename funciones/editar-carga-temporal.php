<?php 
if(!isset($_SESSION)){session_start();}


$id = $_POST['id_teso'];
$desde = $_POST['desde'];
$cobrador = $_POST['cobrador'];
$monto = $_POST['monto'];


if(empty($monto)){
  $monto=0;
}


if(!empty($id))
	{
		  	try
		  	 {
		  	 	
                include '../funciones/conectar-base-de-datos.php';
                $conexion->exec('USE capitulos');
                $sql = 'UPDATE capitulo7_temporal SET fecha="'.$desde.'", cobrador_nombre="'.$cobrador.'", monto='.$monto.'  WHERE id='.$id;
				$query= $conexion->prepare($sql);
				$query->execute();
				
				header("Location: ../vistas/procesos/tesorerias-carga_temporal_capitulo_7.php");
            }
			catch( PDOException $Exception )
			{
			     
			}
	}	