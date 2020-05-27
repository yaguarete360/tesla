<?php if (!isset($_SESSION)) {session_start();}            

function RestarHoras($hora_inicial,$hora_final)
{
  
  $hora_inicial_desglosada = substr($hora_inicial,0,2);
  $minuto_inicial_desglosado = substr($hora_inicial,3,2);
  $segundo_inicial_desglosado = substr($hora_inicial,6,2);
  $hora_final_desglosada = substr($hora_final,0,2);
  $minuto_final_desglosado = substr($hora_final,3,2);
  $segundo_final_desglosado = substr($hora_final,6,2);
  $inicio = ((($hora_inicial_desglosada * 60) * 60) + ($minuto_inicial_desglosado * 60) + $segundo_inicial_desglosado);
  $fin = ((($hora_final_desglosada * 60) * 60) + ($minuto_final_desglosado * 60) + $segf);
  $diferencia  = $fin - $inicio;
  $diferencia_horas = floor($diferencia  / 3600);
  $diferencia_minutos = floor(($diferencia  - ($diferencia_horas * 3600)) / 60);
  $diferencia_segundos = $diferencia  - ($diferencia_minutos * 60) - ($diferencia_horas * 3600);
  $tiempo = date("H:i:s",mktime($difh,$difm,$difs));

  echo $tiempo;

  $_SESSION['tiempo'] = $tiempo;
}

?>
