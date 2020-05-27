<?php if (!isset($_SESSION)) {session_start();}

$cambia_difunto = "";

include $url.'funciones/poner-fechas-en-letras.php';

include $url.'funciones/conectar-base-de-datos.php';                       

$consulta_sucursales = 'SELECT *
  FROM agrupadores
  WHERE borrado LIKE "no"
  AND agrupador LIKE "sucursales" 
  ORDER BY descripcion
  ASC';
$query_sucursales = $conexion->prepare($consulta_sucursales);
$query_sucursales->execute();
while($rows_sucursales = $query_sucursales->fetch(PDO::FETCH_ASSOC))
{
  $sucursal_explotada = explode("-", $rows_sucursales['descripcion']);
  $sucursales[$sucursal_explotada[0]] = $sucursal_explotada[1];
}

$consulta = 'SELECT 
  id, 
  difunto, 
  fecha, 
  nacimiento, 
  tipo, 
  capilla,
  inicio_fecha, 
  inicio_hora, 
  fin_fecha, 
  fin_hora, 
  crematorio, 
  cementerio_origen, 
  cementerio_destino, 
  cementerio_hora
  FROM difuntos
  WHERE borrado LIKE "no"
  AND tipo NOT LIKE "%xhumacion" 
  AND tipo NOT LIKE "%xhunilateral" 
  AND difunto NOT LIKE "*%"
  AND difunto NOT LIKE "sin datos%"
  ORDER BY inicio_fecha
  DESC, difunto
  ASC, tipo
  DESC
  LIMIT 45'
;

$query = $conexion->prepare($consulta);
$query->execute();

$ultima_fecha = NULL;

$vuelta = 0;

$campos_a_usar = array('capilla', 'inicio_fecha', 'inicio_hora', 'fin_fecha', 'fin_hora', 'cementerio_destino', 'cementerio_hora', 'crematorio');
$difuntos_strings_reales = array();
while($rows = $query->fetch(PDO::FETCH_ASSOC))
{
    $difunto = str_replace(' ,', ',', preg_replace('/\s+/', ' ', $rows['difunto']));
    $difuntos_strings_reales[$difunto] = $rows['difunto'];
    $exequias[$rows['inicio_fecha']][$difunto] = "";

    foreach ($campos_a_usar as $pos => $campo_a_usar)
    {
      if($campo_a_usar == "capilla")
      {
        $capilla_explotada = explode("-", $rows[$campo_a_usar]);
        $servicios_por_difunto[$difunto][$rows['tipo']][$campo_a_usar] = $sucursales[$capilla_explotada[0]]." ".$capilla_explotada[1];
      }
      else
      {
        $servicios_por_difunto[$difunto][$rows['tipo']][$campo_a_usar] = $rows[$campo_a_usar];
      }
    }
}

foreach($exequias as $fecha => $difuntos)
{
  echo '<hr/>';
    echo '<center>';
      $fecha_a_usar = date_create($fecha);
      echo '<h5>';
        echo $dia_letras[date_format($fecha_a_usar, 'w')];
        echo ' ';
        echo date_format($fecha_a_usar, 'd');
        echo ' de ';
        echo $mes_letras[date_format($fecha_a_usar, 'n') - 1];
      echo '</h5>';
    echo '</center>';
  echo '<hr/>';
  foreach ($difuntos as $difunto => $vacio)
  {
    echo '<br/>';
    echo '<b>';
      $difunto_string_real = $difuntos_strings_reales[$difunto];
      // $href_a_enviar = (isset($servicios_por_difunto[$difunto]['sepelio'])) ? 'funciones/firmar-libro-de-firmas.php?n='.$difunto_string_real : 'funciones/enviar-email-1-condolencias.php?difunto_del_listado='.$difunto_string_real.'&url=../';
      $href_a_enviar = $url.'funciones/firmar-libro-de-firmas.php?n='.$difunto_string_real;
      // echo '<a href="funciones/enviar-email-1-condolencias.php?difunto_del_listado='.$difunto.'&url=../">';
      // echo '<a href="funciones/firmar-libro-de-firmas.php?n='.$difunto.'">';
      echo '<a href="'.$href_a_enviar.'">';
        echo ' + '.ucwords($difunto);
      echo '</a>';
    echo '</b>'; 
    echo '<br/>';
    echo '<span style="font-style:italic; font-size:10px">';

      if(isset($servicios_por_difunto[$difunto]['sepelio']))
      {
        echo '&nbsp&nbsp&nbsp&nbsp';
        echo 'Velatorio: '.ucwords($servicios_por_difunto[$difunto]['sepelio']['capilla']);
        echo ' de '.substr($servicios_por_difunto[$difunto]['sepelio']['inicio_hora'], 0, 5);
        echo ' del '.substr($servicios_por_difunto[$difunto]['sepelio']['inicio_fecha'],8,2);
        echo ' a '.substr($servicios_por_difunto[$difunto]['sepelio']['fin_hora'], 0, 5);
        echo ' del '.substr($servicios_por_difunto[$difunto]['sepelio']['fin_fecha'],8,2);
        echo '<br/>';
        if(!isset($servicios_por_difunto[$difunto]['inhumacion']) and !isset($servicios_por_difunto[$difunto]['cremacion']))
        {
            echo '&nbsp&nbsp&nbsp&nbsp';
            echo 'Entierro: '.ucwords($servicios_por_difunto[$difunto]['sepelio']['cementerio_destino']);
            echo ', a las '.substr($servicios_por_difunto[$difunto]['sepelio']['cementerio_hora'], 0, 5);
            echo ' del '.substr($servicios_por_difunto[$difunto]['sepelio']['fin_fecha'],8,2);
            echo '<br/>';
        }
      }

      if(isset($servicios_por_difunto[$difunto]['cremacion']))
      {
        echo '&nbsp&nbsp&nbsp&nbsp';
        (strtolower($servicios_por_difunto[$difunto]['cremacion']['crematorio']) == "sin datos") ? $crematorioFinal = "Parque Serenidad, Villa Elisa": $crematorioFinal = $servicios_por_difunto[$difunto]['cremacion']['crematorio'];
        echo 'Cremacion: '.$crematorioFinal;
        echo ', a las '.substr($servicios_por_difunto[$difunto]['cremacion']['cementerio_hora'], 0, 5);
        echo ' del '.substr($servicios_por_difunto[$difunto]['cremacion']['fin_fecha'],8,2);
        echo '<br/>';
      }

      if(isset($servicios_por_difunto[$difunto]['inhumacion']))
      {
        echo '&nbsp&nbsp&nbsp&nbsp';
        echo 'Entierro: '.ucwords($servicios_por_difunto[$difunto]['inhumacion']['cementerio_destino']);
        echo ', a las '.substr($servicios_por_difunto[$difunto]['inhumacion']['cementerio_hora'], 0, 5);
        echo ' del '.substr($servicios_por_difunto[$difunto]['inhumacion']['fin_fecha'],8,2);
        echo '<br/>';
      }

    echo '</span>';
  }
}

?>
