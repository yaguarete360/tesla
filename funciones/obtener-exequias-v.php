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
  WHERE borrado = "no"
  AND tipo NOT LIKE "%xhumacion" 
  AND tipo NOT LIKE "%xhunilateral" 
  AND difunto NOT LIKE "*%"
  AND difunto NOT LIKE "sin datos%"
  ORDER BY inicio_fecha
  DESC, difunto
  ASC, tipo
  DESC
  LIMIT 40'
;

// AND oculto = "no"

$query = $conexion->prepare($consulta);
$query->execute();

$ultima_fecha = NULL;

$vuelta = 0;

while($rows = $query->fetch(PDO::FETCH_ASSOC))
{
    $exequias['difunto'][$vuelta] = ucwords($rows['difunto']);    
    $exequias['fecha'][$vuelta] = $rows['fecha'];
    $exequias['tipo'][$vuelta] = ucwords($rows['tipo']);
    $exequias['capilla'][$vuelta] = ucwords($rows['capilla']);
    $exequias['inicio_fecha'][$vuelta] = $rows['inicio_fecha'];
    $exequias['inicio_hora'][$vuelta] = substr($rows['inicio_hora'],0,-3);
    $exequias['fin_fecha'][$vuelta] = $rows['fin_fecha'];
    $exequias['fin_hora'][$vuelta] = substr($rows['fin_hora'],0,-3);
    $exequias['cementerio_destino'][$vuelta] = ucwords($rows['cementerio_destino']);
    $exequias['cementerio_hora'][$vuelta] = substr($rows['cementerio_hora'],0,-3);
    $exequias['crematorio'][$vuelta] = ucwords($rows['crematorio']);
    $vuelta++;
}

foreach($exequias['difunto'] as $vuelta => $exequia)
{
  if($exequias['inicio_fecha'][$vuelta] != $ultima_fecha)
  {                                             
    echo '<hr/>';
      echo '<center>';
        $ultima_fecha = $exequias['inicio_fecha'][$vuelta];
        $fecha = date_create($ultima_fecha);
        echo '<h5>';
          echo $dia_letras[date_format($fecha, 'w')];
          echo ' ';
          echo date_format($fecha, 'd');
          echo ' de ';
          echo $mes_letras[date_format($fecha, 'n') - 1];
        echo '</h5>';
      echo '</center>';
    echo '<hr/>';
  }
  
  $difunto = $exequias['difunto'][$vuelta];
  
  if(trim($difunto) != trim($cambia_difunto))
  {
    echo '<br/>';
    echo '<b>';
      echo '<a href="funciones/enviar-email-1-condolencias.php?difunto_del_listado='.$exequias['difunto'][$vuelta].'&url=../">';
        echo ' + '.$exequias['difunto'][$vuelta];
      echo '</a>';
    echo '</b>'; 
    echo '<br/>';    
    $cambia_difunto = trim($difunto);
  }
  
  echo '<span style="font-style:italic; font-size:10px">';      
    if(strtolower($exequias['capilla'][$vuelta]) != "no aplicable")
    {
      echo '&nbsp&nbsp&nbsp&nbsp';
      echo 'Velatorio: '.$exequias['capilla'][$vuelta];
      echo ' de '.$exequias['inicio_hora'][$vuelta];
      echo ' a '.$exequias['fin_hora'][$vuelta];
      echo ' del '.substr($exequias['fin_fecha'][$vuelta],8,2);
      echo '<br/>';
    } 
    
    if(strtolower($exequias['cementerio_destino'][$vuelta]) != "no aplicable")
    {
      echo '&nbsp&nbsp&nbsp&nbsp';
      echo 'Entierro: '.$exequias['cementerio_destino'][$vuelta];
      echo ', a las '.$exequias['cementerio_hora'][$vuelta];
      echo ' del '.substr($exequias['fin_fecha'][$vuelta],8,2);
      echo '<br/>';
    } 
    
    if(strtolower($exequias['crematorio'][$vuelta]) != "no aplicable")
    {
      echo '&nbsp&nbsp&nbsp&nbsp';
      (strtolower($exequias['crematorio'][$vuelta]) == "sin datos") ? $crematorioFinal = "Parque Serenidad, Villa Elisa": $crematorioFinal = $exequias['crematorio'][$vuelta];
      echo 'Cremacion: '.$crematorioFinal;
      echo ', a las '.$exequias['cementerio_hora'][$vuelta];
      echo ' del '.substr($exequias['fin_fecha'][$vuelta],8,2);
      echo '<br/>';
    } 
  echo '</span>';            
}

?>
