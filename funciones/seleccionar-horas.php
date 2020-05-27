<?php if(!isset($_SESSION)) {session_start();}

$valor_horas_s = "00:00:00";
if(isset($rows[$campo_nombre])) $valor_horas_s = $rows[$campo_nombre];
if(isset($valor)) $valor_horas_s = $valor;

$valor_horas_a = explode(":", $valor_horas_s);


echo '<span style="display:block;width:100%;height:100%;">';
	$campo_hora = "hora_".$campo_nombre;
	echo '<select class="horas" id="'.$campo_hora.'" name="'.$campo_hora.'" value="00"/>';
		for ($ih=0; $ih < 24; $ih++)
		{
			if(isset($valor_horas_a[0]) and $ih == $valor_horas_a[0])
			{
				echo '<option value="'.$ih.'"selected>'.$ih.'</option>';
			}
			else
			{
				echo '<option value="'.$ih.'">'.$ih.'</option>';
			}
		}
	echo '</select>';

	echo "&nbsp<b>:</b>&nbsp";

	$campo_minuto = "minuto_".$campo_nombre;
	echo '<select class="horas" id="'.$campo_minuto.'" name="'.$campo_minuto.'" value="00"/>';
		for ($im=0; $im < 60; $im++)
		{
			if(isset($valor_horas_a[1]) and $im == $valor_horas_a[1])
			{
				echo '<option value="'.$im.'"selected>'.$im.'</option>';
			}
			else
			{
				echo '<option value="'.$im.'">'.$im.'</option>';
			}
		}
	echo '</select>';
echo '</span>';

echo '<input type="hidden" id="'.$campo_nombre.'" name="'.$campo_nombre.'" value="'.$valor_horas_s.'">';

?>

<script type="text/javascript">

	var pad = "00";

	document.getElementById('<?php echo $campo_hora; ?>').onchange=function(){
		var hora = document.getElementById('<?php echo $campo_hora; ?>').value;
		var hora_f = pad.substring(0, pad.length - hora.length) + hora;
		var minuto = document.getElementById('<?php echo $campo_minuto; ?>').value;
		var minuto_f = pad.substring(0, pad.length - minuto.length) + minuto;
		document.getElementById('<?php echo $campo_nombre; ?>').value = hora_f+":"+minuto_f+":00";
	};

	document.getElementById('<?php echo $campo_minuto; ?>').onchange=function(){
		var hora = document.getElementById('<?php echo $campo_hora; ?>').value;
		var hora_f = pad.substring(0, pad.length - hora.length) + hora;
		var minuto = document.getElementById('<?php echo $campo_minuto; ?>').value;
		var minuto_f = pad.substring(0, pad.length - minuto.length) + minuto;
		document.getElementById('<?php echo $campo_nombre; ?>').value = hora_f+":"+minuto_f+":00";
	};

</script>
