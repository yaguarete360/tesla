<?php if(!isset($_SESSION)) {session_start();}

$partes_del_codigo = explode("-", $valor);
echo '<span style="display:block;width:100%;height:100%;">';
  echo '<select id="armar-contrato-linea" class="datos">';
    $lineas = array('', '1', '2');
    foreach ($lineas as $pos => $linea)
    {
      ((isset($partes_del_codigo[0]) and $partes_del_codigo[0] == $linea) or $pos == 0) ? $seleccionado = "selected" : $seleccionado = "";
      echo '<option value="'.$linea.'" '.$seleccionado.'>'.$linea.'</option>';
    }
  echo '</select>';
  echo "&nbsp<b>-</b>&nbsp";
    echo '<select id="armar-contrato-centro" class="datos">';
      
      $siglas_del_tipo['cremacion'] = array('', 'psc');
      $siglas_del_tipo['sepelio'] = array('', 'psm', 'psv');
      $siglas_del_tipo['inhumacion'] = array('', 'psi');

      if(isset($vista_tipo))
      {
        foreach ($siglas_del_tipo[$vista_tipo] as $pos => $siglas)
        {
          ((isset($partes_del_codigo[1]) and $partes_del_codigo[1] == $siglas) or $pos == 0) ? $seleccionado = "selected" : $seleccionado = "";
          echo '<option value="'.$siglas.'" '.$seleccionado.'>'.strtoupper($siglas).'</option>';
        }
      }
      elseif(isset($campo_atributo['herramientas']))
      {
        echo '<option value=""></option>';
        $opciones = explode("-", $campo_atributo['herramientas']);
        foreach ($opciones as $pos => $opcion) echo '<option value="'.strtolower($opcion).'">'.strtoupper($opcion).'</option>';
      }
    echo '</select>';
    
    echo "&nbsp<b>-</b>&nbsp";
    (isset($partes_del_codigo[2])) ? $valor_numero = $partes_del_codigo[2] : $valor_numero = "";
    echo '<input type="text" name="armar-contrato-numero" class="datos" maxlength="7" pattern="[0-9]{1,7}" title="Solo numeros" id="armar-contrato-numero" value="'.$valor_numero.'"/>';
echo '</span>';

echo '<span style="display:block;width:100%;height:100%;">';
    echo '<input type="text" id="armar-contrato-destino" name="'.$campo_nombre.'" class="datos" value="'.$valor.'" readonly/>';
echo '</span>';

?>

<script type="text/javascript">
$(document).ready(function()
{

  linea = "1";//este es el valor default
  $('#armar-contrato-linea').change(function()
  {
    linea = this.value;
  });

  centro = "PSM";//este es el valor default
  $('#armar-contrato-centro').change(function()
  {
    centro = this.value;
  });

  numero = "0000000";//este es el valor default
  $('#armar-contrato-numero').keyup(function()
  {
    numero = this.value;
    if(numero == "")
    {
      numero = "0";
    }
    numero = ('0000000'+numero).substring(numero.length);
  });

  $('#armar-contrato-linea,#armar-contrato-centro,#armar-contrato-numero').bind("keyup change", function()
    {
      linea = document.getElementById('armar-contrato-linea').value;
      centro = document.getElementById('armar-contrato-centro').value;

      conFinal = linea+"-"+centro+"-"+numero;
      document.getElementById("armar-contrato-destino").value = conFinal;
    });
});

</script>
