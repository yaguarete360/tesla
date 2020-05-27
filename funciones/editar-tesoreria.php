<?php 
if(!isset($_SESSION)){session_start();}

$id = $_POST['id_teso'];
$anterior = $_POST['anterior'];
$entra = $_POST['entra'];
$sale = $_POST['sale'];
$modulo = $_POST['modulo'];
$fecha=$_POST['fecha'];

if(empty($anterior)){
  $anterior=0;
}
if(empty($entra)){
  $entra=0;
}
if(empty($sale)){
  $sale=0;
}

if(!empty($id))
	{
		  	try
		  	 {
                include '../funciones/conectar-base-de-datos.php';

                $sql = 'UPDATE tesorerias SET saldo_anterior='.$anterior.', entra='.$entra.', sale='.$sale.'  WHERE id='.$id;
				$query= $conexion->prepare($sql);
				$query->execute();
				include "../funciones/insertar-saldo-final-tesoreria.php";
				header("Location: ../vistas/procesos/".$modulo."?fecha=".$fecha);
            }
			catch( PDOException $Exception )
			{
			     
			}
	}		
