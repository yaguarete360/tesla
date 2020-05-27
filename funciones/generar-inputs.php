<?php if (!isset($_SESSION)) {session_start();}

$valor  = $rows[$campo_nombre];
$variable_nombre = $campo_nombre;
$requerido = (isset($campo_atributo['requerido']) and $campo_atributo['requerido'] == "si") ? "required" : "";

if(isset($campo_atributo['formato']) and $campo_atributo['formato'] != "oculto")
{
    if(!isset($campo_atributo['mostrar_etiqueta']) or (isset($campo_atributo['mostrar_etiqueta']) and $campo_atributo['mostrar_etiqueta'] == "si"))
    {
    	echo '<td id="td-label-'.$campo_nombre.'">';
    		echo '<b><label for="'.$campo_nombre.'">'.str_replace("_"," ",ucwords($campo_nombre)).'</label></b> &nbsp &nbsp';
    	echo '</td>';
    }
}

echo '<td>';
	switch ($campo_atributo['formato']) 
	{	
		case 'oculto':
			if(isset($campo_atributo['selector']) and $campo_atributo['selector'] == "asistido")
			{
				include "../funciones/generar-inputs-asistidos.php";
			}
			else
			{
				echo '<input type="hidden" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value="'.$rows[$campo_nombre].'"/>';
			}
		break;

		case 'vista':
			if(isset($campo_atributo['selector']) and $campo_atributo['selector'] == "asistido")
			{
				include "../funciones/generar-inputs-asistidos.php";
			}
			else
			{
				echo $rows[$campo_nombre];
		 		echo '<input type="hidden" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value="'.$rows[$campo_nombre].'" '.$requerido.'/>';
			}
		break;

		case 'texto':
			if(isset($campo_atributo['selector']) and $campo_atributo['selector'] == "asistido")
			{
				include "../funciones/generar-inputs-asistidos.php";
			}
			else
			{
				echo '<input type="text" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value="'.$rows[$campo_nombre].'" '.$requerido.'/>';
			}
		break;

		case 'numero':
			if(isset($campo_atributo['selector']) and $campo_atributo['selector'] == "asistido")
			{
				include "../funciones/generar-inputs-asistidos.php";
			}
			else
			{
				echo '<input type="text" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value="'.$rows[$campo_nombre].'" '.$requerido.'/>';
			}
		break;

		case 'numero-texto':
			if(isset($campo_atributo['selector']) and $campo_atributo['selector'] == "asistido")
			{
				include "../funciones/generar-inputs-asistidos.php";
			}
			else
			{
				echo '<input type="text" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value="'.$rows[$campo_nombre].'" '.$requerido.'/>';
			}
		break;

		case 'fecha':
		    
			$_SESSION[$variable_nombre] = $rows[$campo_nombre];
			$rotulo = "";
			$indice = $ind;
			include "../funciones/seleccionar-fechas.php";
		
		break;
		
		case 'texto-caja':
	       echo '<textarea name="'.$campo_nombre.'" id="'.$campo_nombre.'">'.$rows[$campo_nombre].'</textarea>';
		break;
		
		default:			
			echo '<input type="text" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value="'.$rows[$campo_nombre].'" '.$requerido.'/><br/>';
		break;

	}
echo '</td>';