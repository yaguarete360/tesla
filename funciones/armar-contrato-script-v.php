<?php if(!isset($_SESSION)) {session_start();}

echo '<span style="display:block;width:100%;height:100%;">';
  echo '<select id="armar-contrato-linea" class="datos">';
    echo '<option value="1">1</option>';
    echo '<option value="2">2</option>';
  echo '</select>';
  echo "&nbsp<b>-</b>&nbsp";
    echo '<select id="armar-contrato-centro" class="datos">';
      if(isset($vista_tipo))
      {
        switch (strtolower($vista_tipo))
        {
          case 'cremacion':
            echo '<option value="PSC">PSC</option>';
          break;

          case 'sepelio':
            echo '<option value="PSM">PSM</option>';
            echo '<option value="PSV">PSV</option>';
          break;
          
          case 'inhumacion':
            echo '<option value="PSI">PSI</option>';
          break;
          
          default:
            echo '<option value=""></option>';
          break;
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

    echo '<input type="text" name="armar-contrato-numero" class="datos" maxlength="7" pattern="[0-9]{1,7}" title="Solo numeros" id="armar-contrato-numero" value=""/>';
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
