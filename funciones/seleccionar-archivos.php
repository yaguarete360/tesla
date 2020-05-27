<?php if(!isset($_SESSION)) {session_start();}

if($rotulo != "") echo '<b><label for="'.$variable_nombre.'">'.ucfirst($rotulo).'</label></b>';

echo '<select name="'.$variable_nombre.'" value="'.$valor.'"/>';
	include $url.'pse-red/funciones/conectar-base-de-datos.php';
	
	if(isset($filtro_ano_mes))
	{
		$_SESSION['ano'] = $ano;
		if(isset($mes))
		{		
			$_SESSION['mes'] = $mes;
			$consulta_seleccion = 'SELECT * 
			FROM '.$tabla_seleccion.' 
			WHERE borrado = "0000-00-00"
			AND fecha LIKE "'.$ano.'-'.$mes.'%" 
			ORDER BY '.$campo_seleccion.' '.
			$sentido
			;
		}
		else
		{
			$consulta_seleccion = 'SELECT * 
			FROM '.$tabla_seleccion.' 
			WHERE borrado = "0000-00-00"
			AND fecha LIKE "'.$ano.'%" 
			ORDER BY '.$campo_seleccion.' '.
			$sentido
			;
		}
	}
	else
	{
		if(isset($filtroAlfabeto))
		{
			$limite_inferior = $_POST['millar'] * 1000;
			$limite_superior = $limite_inferior + 1000;
			if(isset($limiteEnMillares))
			{
				$consulta_seleccion = 'SELECT * 
				FROM '.$tabla_seleccion.' 
				WHERE borrado = "0000-00-00"
				AND '.$filtroAlfabeto.' LIKE "'.$_POST['letra'].'%"
				AND '.$limiteEnMillares.' BETWEEN '.$limite_inferior.'
				AND '.$limite_superior.'
				GROUP BY '.$limiteEnMillares.'  
				ORDER BY '.$campo_seleccion.' '.
				$sentido
				;
			}
			else
			{
				$consulta_seleccion = 'SELECT * 
				FROM '.$tabla_seleccion.' 
				WHERE borrado = "0000-00-00"
				AND '.$filtroAlfabeto.' LIKE "'.$_POST['letra'].'%"  
				ORDER BY '.$campo_seleccion.' '.
				$sentido
				;
			}
		}
		else
		{
			$consulta_seleccion = 'SELECT * 
			FROM '.$tabla_seleccion.' 
			WHERE borrado = "0000-00-00" 
			ORDER BY '.$campo_seleccion.' '.
			$sentido
			;
		}		
	}
	
	$query_seleccion = $conexion->prepare($consulta_seleccion);
	$query_seleccion->execute();
	
	if($blanco === "si") echo '<option value=""></option>';		
	if($todos === "si") echo '<option value="Todos">Todos</option>';
	if(isset($tiene_sin_dato) and $tiene_sin_dato == "si") echo '<option value="No Se Uso">No Se Uso</option>';
	
	while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC))
	{
		if($rows_seleccion[$campo] === $valor)
		{
			echo '<option value="'.$rows_seleccion[$campo].'"selected>'.$rows_seleccion[$campo].'</option>';
		}
		else
		{
			echo '<option value="'.$rows_seleccion[$campo].'">'.$rows_seleccion[$campo].'</option>';
		}
	}	

echo '</select>';

?>
