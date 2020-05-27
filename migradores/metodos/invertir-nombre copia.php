<?php if (!isset($_SESSION)) {session_start();}

$valor = $rows_origen[$campo_atributo['campo']];

if(!empty($rows_origen[$campo_atributo['campo']]))
{	
	
	$valor = strtolower($valor);

	$casadas_de = explode(" de ", $valor);
	$casadas_de_de = explode(" de de ", $valor);

	if(isset($casadas_de[1]) or isset($casadas_de_de[1]))
	{
		
		if(isset($casadas_de[1]) and !isset($casadas_de_de[1]))
		{		
			$nombres = explode(" ", $valor);

			for($i = 0; $i < 10; $i++)
			{
				if(isset($nombres[$i]))
				{				
					if($nombres[$i] == "de") $posicion_del_de = $i;

					if(isset($posicion_del_de) and $posicion_del_de > 1)
					{
						$nombre_0 = isset($nombres[0]) ? trim($nombres[0]) : "";
						$nombre_1 = isset($nombres[1]) ? trim($nombres[1]) : "";
						$nombre_2 = isset($nombres[2]) ? trim($nombres[2]) : "";
						$nombre_3 = isset($nombres[3]) ? trim($nombres[3]) : "";
						$nombre_4 = isset($nombres[4]) ? trim($nombres[4]) : "";
						$nombre_5 = isset($nombres[5]) ? trim($nombres[5]) : "";
						$nombre_6 = isset($nombres[6]) ? trim($nombres[6]) : "";
						$nombre_7 = isset($nombres[7]) ? trim($nombres[7]) : "";
						$nombre_8 = isset($nombres[8]) ? trim($nombres[8]) : "";

						if($posicion_del_de == 2) $valor = $nombre_1.' de '.$nombre_3.', '.$nombre_0;
						if($posicion_del_de == 3) $valor = $nombre_2.' de '.$nombre_4.', '.$nombre_0.' '.$nombre_1;
						if($posicion_del_de == 4) $valor = $nombre_3.' de '.$nombre_5.', '.$nombre_0.' '.$nombre_1.' '.$nombre_2;
						if($posicion_del_de == 5) $valor = $nombre_4.' de '.$nombre_6.', '.$nombre_0.' '.$nombre_1.' '.$nombre_2.' '.$nombre_3;
						if($posicion_del_de == 6) $valor = $nombre_5.' de '.$nombre_7.', '.$nombre_0.' '.$nombre_1.' '.$nombre_2.' '.$nombre_3.' '.$nombre_4;
						if($posicion_del_de == 7) $valor = $nombre_6.' de '.$nombre_8.', '.$nombre_0.' '.$nombre_1.' '.$nombre_2.' '.$nombre_3.' '.$nombre_4.' '.$nombre_5;
					}
					else
					{
						$valor = $nombres[0];
					}

				} 
			}
		}
		else
		{
			$nombres = explode(" ", $valor);

			for($i = 0; $i < 10; $i++)
			{
				if(isset($nombres[$i]))
				{				
					if($nombres[$i] == "de") $posicion_del_de = $i;

					if(isset($posicion_del_de) and $posicion_del_de > 1)
					{
						$nombre_0 = isset($nombres[0]) ? trim($nombres[0]) : "";
						$nombre_1 = isset($nombres[1]) ? trim($nombres[1]) : "";
						$nombre_2 = isset($nombres[2]) ? trim($nombres[2]) : "";
						$nombre_3 = isset($nombres[3]) ? trim($nombres[3]) : "";
						$nombre_4 = isset($nombres[4]) ? trim($nombres[4]) : "";
						$nombre_5 = isset($nombres[5]) ? trim($nombres[5]) : "";
						$nombre_6 = isset($nombres[6]) ? trim($nombres[6]) : "";
						$nombre_7 = isset($nombres[7]) ? trim($nombres[7]) : "";
						$nombre_8 = isset($nombres[8]) ? trim($nombres[8]) : "";

						if($posicion_del_de == 3) $valor = $nombre_1.' '.$nombre_2.' de '.$nombre_4.', '.$nombre_0;
						if($posicion_del_de == 4) $valor = $nombre_2.' '.$nombre_3.' de '.$nombre_5.', '.$nombre_0.' '.$nombre_1;
						if($posicion_del_de == 5) $valor = $nombre_3.' '.$nombre_4.' de '.$nombre_6.', '.$nombre_0.' '.$nombre_1.' '.$nombre_2;
						if($posicion_del_de == 6) $valor = $nombre_4.' '.$nombre_5.' de '.$nombre_7.', '.$nombre_0.' '.$nombre_1.' '.$nombre_2.' '.$nombre_3;
						if($posicion_del_de == 7) $valor = $nombre_5.' '.$nombre_6.' de '.$nombre_8.', '.$nombre_0.' '.$nombre_1.' '.$nombre_2.' '.$nombre_3.' '.$nombre_4;
					}
					else
					{
						$valor = trim($nombres[0]);
					}

				} 
			}			
		}
	}
	else
	{		

		$nombres = explode(" ",$valor);	
		$cantidad = count($nombres);

		if(strpos($valor, " del ") == 0)
		{			
			$nombre_0 = isset($nombres[0]) ? trim($nombres[0]) : "";
			$nombre_1 = isset($nombres[1]) ? trim($nombres[1]) : "";
			$nombre_2 = isset($nombres[2]) ? trim($nombres[2]) : "";
			$nombre_3 = isset($nombres[3]) ? trim($nombres[3]) : "";
			$nombre_4 = isset($nombres[4]) ? trim($nombres[4]) : "";
			$nombre_5 = isset($nombres[5]) ? trim($nombres[5]) : "";
			$nombre_6 = isset($nombres[6]) ? trim($nombres[6]) : "";
			$nombre_7 = isset($nombres[7]) ? trim($nombres[7]) : "";
			$nombre_8 = isset($nombres[8]) ? trim($nombres[8]) : "";
			
			if($cantidad == 1) $valor = $nombre_0.', sin datos';	
			if($cantidad == 2) $valor = $nombre_1.', '.$nombre_0;	
			if($cantidad == 3) $valor = $nombre_2.', '.$nombre_0.' '.$nombre_1;
			if($cantidad == 4) $valor = $nombre_2.' '.$nombre_3.', '.$nombre_0.' '.$nombre_1;
			if($cantidad == 5) $valor = $nombre_2.' '.$nombre_3.' '.$nombre_4.', '.$nombre_0.' '.$nombre_1;
			if($cantidad == 6) $valor = $nombre_2.' '.$nombre_3.' '.$nombre_4.' '.$nombre_5.', '.$nombre_0.' '.$nombre_1;
			if($cantidad >= 7) $valor = $nombre_2.' '.$nombre_3.' '.$nombre_4.' '.$nombre_5.' '.$nombre_6.', '.$nombre_0.' '.$nombre_1;
		}
		else
		{
			if($cantidad == 4) $valor = $nombre_3.', '.$nombre_0.' '.$nombre_1.' '.$nombre_2;
			if($cantidad >= 5) $valor = $nombre_3.' '.$nombre_4.', '.$nombre_0.' '.$nombre_1.' '.$nombre_2;
		}
	}

	
	$valor = str_replace("¥","ñ",$valor);

}
else
{
	$valor = "sin datos";
}
	
?>
