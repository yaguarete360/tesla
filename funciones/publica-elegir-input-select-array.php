<?php if (!isset($_SESSION)) {session_start();}

if(!isset($devolver_como_get) and isset($_GET[$variable])) $_SESSION[$variable] = $_GET[$variable];

if($obligatorio) $obligatorio = "required";
if(isset($es_imagen) and $es_imagen == "si") $activar_script = 'id="select_imagen"'; 
if(isset($es_documento) and $es_imagen == "si") $activar_script = 'id="select_documento"'; 

echo '<select '.$activar_script.' class="elegir" name="'.$variable.'" value="'.$_SESSION[$variable].'" '.$obligatorio.'>';

	if(isset($opcion_en_blanco) and $opcion_en_blanco == "si") echo '<option value=""></option>';
    
    if(isset($_SESSION[$variable]))
    {
        echo '<option value="'.$_SESSION[$variable].'" selected>'.ucwords(str_replace("_"," ",$_SESSION[$variable])).'</option>';
    }           
    foreach ($opciones as $opcion) 
    {
		if(trim( $opcion)[0] != ".") echo '<option value="'.$opcion.'">'.ucwords(str_replace("_"," ",$opcion)).'</option>';
    }
echo '</select>';

?>
