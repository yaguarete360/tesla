<?php if (!isset($_SESSION)) {session_start();}

echo '<hr/>Armando la insercion<hr/>';

include "../migradores/funciones/conectar-mig.php";

if(!empty($campo_disyuntor))
{
	$origen = 'SELECT * 
	FROM  '.$tabla_de_origen.'
	WHERE id BETWEEN "'.$desde_el_registro.'" 
	AND "'.$hasta_el_registro.'"
	AND '.$campo_disyuntor.' > 0
	ORDER BY id 
	ASC';
}
else
{
	$origen = 'SELECT * 
	FROM  '.$tabla_de_origen.'
	WHERE id BETWEEN "'.$desde_el_registro.'" 
	AND "'.$hasta_el_registro.'"
	ORDER BY id 
	ASC';	
}

$query_origen = $conexion_mig->prepare($origen);
$query_origen->execute();

$ind = 0;

echo "<br/>";

while($rows_origen = $query_origen->fetch(PDO::FETCH_ASSOC))
{			

$control_origen[$ind] = $tabla_de_origen;
if(!empty($campo_disyuntor)) $control_origen[$ind].= '-'.trim(strtolower($campo_disyuntor));
if(!empty($doble_disyuntor)) $control_origen[$ind].= '-'.trim(strtolower($doble_disyuntor));
$control_origen[$ind].= '*'.$rows_origen['id'];
	
	if(isset($control_destino) and in_array($control_origen[$ind],$control_destino))
	{
		echo '<span style="color: red;">';
			echo $control_origen[$ind]. ' ya existe en la tabla de DESTINO.';
		echo '</span>';
		echo "<br/>"; 
	}
	else
	{		
		$inserciones[$ind] = "INSERT INTO ".$tabla_de_destino." (";
		
		foreach($campos as $campo_nombre => $campo_atributo)
		{	
			$inserciones[$ind].= '`'.$campo_nombre.'`,';
		}

		$inserciones[$ind].= ")";
		
		$inserciones[$ind] = str_replace(",)",")",$inserciones[$ind]);
		
		$inserciones[$ind].= ' VALUES(';			
		

		foreach($campos as $campo_nombre => $campo_atributo)
		{														
			
			$valor = "";

			include '../migradores/metodos/'.$campo_atributo['metodo'].'.php';


			$inserciones[$ind].= '"'.$valor.'",'; 
		}

		$inserciones[$ind].= ');';
		
		$inserciones[$ind] = str_replace(",);",");",$inserciones[$ind]);

		echo '<span style="color: blue; font-size: 20px; font-weight: bold">';
			echo "-- Insertando el id --->";
			echo $rows_origen['id'];
		echo '</span>';	
		echo "<br/>";
		echo '<span style="color: green;">';
			echo $inserciones[$ind];
		echo '</span>';	
		echo "<br/>";
		
		$ind++;
	}

}

?>
