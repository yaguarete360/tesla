<?php if(!isset($_SESSION)) {session_start();}

  $ultimo = 0;

  $consulta_seleccion = 'SELECT numero_papeleria, tipo FROM difuntos
    WHERE borrado LIKE "no"
    AND numero_papeleria NOT LIKE "sin datos"
    AND numero_papeleria NOT LIKE "sd"
    AND numero_papeleria NOT LIKE "s/d"
    AND numero_papeleria NOT LIKE "s/n"
    AND LOWER(tipo) = "'.strtolower($_SESSION['vista_tipo']).'"
    ORDER BY ABS(numero_papeleria)
    DESC LIMIT 1';
    
  $query_seleccion = $conexion->prepare($consulta_seleccion);
  $query_seleccion->execute();

  while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC))
  {
    $ultimo = $rows_seleccion['numero_papeleria'];
  }

  $ultimo_1 = substr($ultimo, 0, 1);

  if($ultimo_1 == "T" or $ultimo_1 == "C" or $ultimo_1 == "I")
  {
    $numero_a_sumar = substr($ultimo, 1);
    $numero_final = $numero_a_sumar + 1;
  }
  else
  {
    $numero_final = $ultimo + 1;
  }

?>
