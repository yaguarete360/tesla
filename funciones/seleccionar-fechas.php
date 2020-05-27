<?php if(!isset($_SESSION)) {session_start();}

include $url.'funciones/cargar-datepicker.php';

if(!isset($_SESSION[$variable_nombre])) $_SESSION[$variable_nombre] = "";
if(!isset($requerido)) $requerido = "";

if($rotulo != "") echo '<b><label for="'.$variable_nombre.'">'.ucfirst($rotulo).'</label></b>';

echo '<input class="datos" id="datepicker'.$indice.'"';
echo 'onchange=CambiarFecha(this.value,'.$indice.') type="date"';
echo 'name="'.$variable_nombre.'" value="'.$_SESSION[$variable_nombre].'"';
echo 'placeholder="'.ucfirst($rotulo).'" '.$requerido.'/>';
?>