<?php if(!isset($_SESSION)) {session_start();}

$centro_codigo = "";

switch(strtolower($_SESSION['vista_tipo']))
{
  case 'sepelio':
    $centro_codigo = "sds";
  break;
  case 'exhumacion':
    $centro_codigo = "exh";
  break;
  case 'exunilateral':
    $centro_codigo = "exu";
  break;
  case 'inhumacion':
    $centro_codigo = "inh";
  break;
  case 'cremacion':
    $centro_codigo = "sdc";
  break;
  case 'traslado':
    $centro_codigo = "sdt";
  break;
  default:
    $centro_codigo = "error";
  break;
}

  $xc1 = 0;
  $xc2 = 0;
  $control = date("Y").date("m");
  $codigo_pse = array();
  $codigo_mem = array();

  $consulta_seleccion = 'SELECT codigo, borrado, tipo
  FROM difuntos
  WHERE borrado LIKE "no"
  AND LOWER(tipo) LIKE "'.strtolower($_SESSION['vista_tipo']).'"
  ORDER BY codigo
  ASC';

  $query_seleccion = $conexion->prepare($consulta_seleccion);
  $query_seleccion->execute();

  while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC))
  {
    $codigo_partes = explode("-", $rows_seleccion['codigo']);
    if(isset($codigo_partes[2]))
    {
      if($codigo_partes[0] == "1")
      {
        if(substr($codigo_partes[2], 0, 6) == $control)
        {
          $codigo_pse[$xc1] = $rows_seleccion['codigo'];
          $xc1++;
        }
      }
      elseif($codigo_partes[0] == "2")
      {
        if(substr($codigo_partes[2], 0, 6) == $control)
        {
          $codigo_mem[$xc2] = $rows_seleccion['codigo'];
          $xc2++;
        }
      }
      else
      {
        $codigoFinal = "Falta Codigo";
      }
    }
  }

$ultimo_pse = substr(end($codigo_pse),-3) + 1;
$ultimo_mem = substr(end($codigo_mem),-3) + 1;
$valor_defecto = "1-".$centro_codigo."-".$control.str_pad($ultimo_pse, 3, "0", STR_PAD_LEFT);

(isset($valor) and !empty($valor)) ? $partes_del_codigo = explode("-", $valor) : $partes_del_codigo = explode("-", $valor_defecto);

  $chequeado = "";
  $chequeado_nuevo = "";
  if(!isset($partes_del_codigo[0])) $chequeado_nuevo = "checked";
  if(isset($partes_del_codigo[0]) and $partes_del_codigo[0] == "1") $chequeado = "checked";
  echo 'Parque:   <input type="radio" id="selector_linea_1" name="selector_linea" class="datos" value="1" '.$chequeado.$chequeado_nuevo.'/>';
  
  echo '&nbsp&nbsp&nbsp';
  $chequeado = "";
  if(isset($partes_del_codigo[0]) and $partes_del_codigo[0] == "2") $chequeado = "checked";
  echo 'Memorial: <input type="radio" id="selector_linea_2" name="selector_linea" class="datos" value="2" '.$chequeado.'/>';
echo '</td>';

echo '</tr><tr>';//acomodador de tr
echo '<td '.$estilo_del_td.'></td>';//acomodador de td

echo '<td id="td_'.$campo_nombre.'" class="td_columna2" '.$estilo_del_td.'>';
  ($subtitulo == "difuntos-modificar") ? $editable = "" : $editable = "readonly";
  echo '<input type="text" name="'.$campo_nombre.'" class="datos" id="armar-codigo" value="'.$valor_defecto.'" '.$editable.'/>';
echo '</td>';
echo '</tr><tr>';//acomodador de tr

echo '<td '.$estilo_del_td.'>';
echo '</td>';

echo '<td '.$estilo_del_td.'>';
  echo "<b>Ultimos:&nbsp&nbsp&nbsp</b>";
  echo "<b>PSE:</b> ".end($codigo_pse)." <b>MEM:</b> ".end($codigo_mem);
echo '</td>';

?>

<script type="text/javascript">
  var control = '<?php echo $control; ?>';
  var ultimo_pse = '<?php echo $ultimo_pse; ?>';
  var ultimo_mem = '<?php echo $ultimo_mem; ?>';
  var centro_codigo = '<?php echo $centro_codigo; ?>';

$(document).ready(function()
{
  if(document.getElementById("linea").value)
  {}
  else
  {
    if(document.getElementById("selector_linea_1").checked)
    {
      document.getElementById("linea").value = "1";
    }
    else if(document.getElementById("selector_linea_2").checked)
    {
      document.getElementById("linea").value = "2";
    }
  }
});

  document.getElementById("selector_linea_1").onclick=function(){
    var ultimo_pse_f = ('000'+ultimo_pse).substring(ultimo_pse.length);
    document.getElementById("armar-codigo").value = "1-"+centro_codigo+"-"+control+ultimo_pse_f;
    document.getElementById("linea").value = "1";
    };
    
  document.getElementById("selector_linea_2").onclick=function(){
    var ultimo_mem_f = ('000'+ultimo_mem).substring(ultimo_mem.length);
    document.getElementById("armar-codigo").value = "2-"+centro_codigo+"-"+control+ultimo_mem_f;
    document.getElementById("linea").value = "2";
    };

</script>
