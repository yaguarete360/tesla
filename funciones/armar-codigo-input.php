<?php if(!isset($_SESSION)) {session_start();}
  
  $valor_a_usar = "";
  if(isset($rows[$campo_nombre])) $valor_a_usar = $rows[$campo_nombre];
  if(isset($valor)) $valor_a_usar = $valor;
  $valores = explode("-", $valor_a_usar);

  echo '<select id="'.$campo_nombre.'_selector_linea" style="width:20%;">';
    for ($i=1; $i < 3; $i++)
    { 
      (isset($valores[0]) and $valores[0] == $i) ? $seleccionado = 'selected': $seleccionado = "";
      echo '<option value="'.$i.'" '.$seleccionado.'>'.$i.'</option>';
    }
  echo '</select>';
  
  echo "&nbsp&nbsp-&nbsp&nbsp";
  
  $tipos_de_servicios_s = "sds,exh,inh,exu,sdc,sdt";
  $tipos_de_servicios_a = explode(",",$tipos_de_servicios_s);
  asort($tipos_de_servicios_a);
  echo '<select id="'.$campo_nombre.'_selector_producto" style="width:20%;">';
    foreach ($tipos_de_servicios_a as $pos => $valor_producto)
    {
      (isset($valores[1]) and $valores[1] == $valor_producto) ? $seleccionado = 'selected': $seleccionado = "";
      echo '<option value="'.$valor_producto.'" '.$seleccionado.'>'.$valor_producto.'</option>';
    }
  echo '</select>';

  echo "&nbsp&nbsp-&nbsp&nbsp";

  (isset($valores[2])) ? $valor_numero = $valores[2] : $valor_numero = "";
  echo '<input type="text" class="datos" id="'.$campo_nombre.'_selector_numero" maxlength="9" style="width:48%;" value="'.$valor_numero.'"/>';

  echo '<br/>';
  echo '<span id="'.$campo_nombre.'_span_objetivo"><b>Codigo:</b>'.$valor_a_usar.'</span>';
  echo '<input type="hidden" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'_selector_objetivo" value="'.$valor_a_usar.'"/>';

?>

<script type="text/javascript">
  
  var campo_nombre = '<?php echo $campo_nombre; ?>';

  $('#'+campo_nombre+'_selector_linea,#'+campo_nombre+'_selector_producto,#'+campo_nombre+'_selector_numero').change(function()
  {
    var linea_a_usar = $('#'+campo_nombre+'_selector_linea').val();
    var producto_a_usar = $('#'+campo_nombre+'_selector_producto').val();
    var numero_a_usar = $('#'+campo_nombre+'_selector_numero').val();

    valor_final = "sin datos";
    if(numero_a_usar.length == "9")
    {
      var valor_final = linea_a_usar+"-"+producto_a_usar+"-"+numero_a_usar;
    }

    $('#'+campo_nombre+'_span_objetivo').html("<b>Codigo:</b> "+valor_final);
    $('#'+campo_nombre+'_selector_objetivo').val(valor_final);

  });

</script>
