<script type="text/javascript">
$(document).ready(function()
{
	var feretros_modelos = '<?php echo json_encode($feretros_modelos); ?>';
	var feretros_modelos = JSON.parse(feretros_modelos);

	var feretros_medidas = '<?php echo json_encode($feretros_medidas); ?>';
	var feretros_medidas = JSON.parse(feretros_medidas);

	var feretros_fechas = '<?php echo json_encode($feretros_fechas); ?>';
	var feretros_fechas = JSON.parse(feretros_fechas);

	document.getElementById("feretro_serie").onchange=function(){
		primeraSeleccion = document.getElementById("feretro_serie").selectedIndex;
		document.getElementById("feretro_modelo").value = feretros_modelos[primeraSeleccion];
		document.getElementById("feretro_medida").value = feretros_medidas[primeraSeleccion];
		document.getElementById("feretro_fecha").value = feretros_fechas[primeraSeleccion];
	};
});
</script>