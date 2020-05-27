<?php if (!isset($_SESSION)) {session_start();}

$cambia_difunto = "";

include $url.'funciones/poner-fechas-en-letras.php';

include $url.'funciones/conectar-base-de-datos.php';                       

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
  LIMIT 100'
;

$query = $conexion->prepare($consulta);
$query->execute();

$ultima_fecha = NULL;

$vuelta = 0;

$campos_a_usar = array('capilla', 'inicio_fecha', 'inicio_hora', 'fin_fecha', 'fin_hora', 'cementerio_destino', 'cementerio_hora', 'crematorio');
while($rows = $query->fetch(PDO::FETCH_ASSOC))
{
    $exequias[$rows['inicio_fecha']][$rows['difunto']] = "";

    foreach ($campos_a_usar as $pos => $campo_a_usar)
    {
      $servicios_por_difunto[$rows['difunto']][$rows['tipo']][$campo_a_usar] = $rows[$campo_a_usar];;
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
      echo '<a href="funciones/enviar-email-1-condolencias.php?difunto_del_listado='.$difunto.'&url=../">';
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
