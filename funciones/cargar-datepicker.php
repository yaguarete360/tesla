<script type="text/javascript">

	$().ready(function() 
	{
		$(function() 
		{
			for (i = 0; i < 100; ++i) {				
			$('#datepicker'+i).datepicker({changeMonth: true, changeYear: true, dateFormat: 'yy-mm-dd'});
		}
		});
	});
	function CambiarFecha(fecha_nueva,ind)
	{
		var fecha_nueva = fecha_nueva;
		var ind = ind;
		document.getElementById("datepicker"+ind).setAttribute('value',fecha_nueva);
	}
</script>