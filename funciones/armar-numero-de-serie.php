<?php if(!isset($_SESSION)) {session_start();}

if(isset($rows[$campo_nombre]))
{
  $valor = $rows[$campo_nombre];
}
else
{
  $sequencia_de_letras_s = "a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z";
  $sequencia_de_letras_a = explode(",", $sequencia_de_letras_s);

  $i_series = 0;
  $datos_a_usar = explode("-", $campo_atributo['herramientas']);
  $tabla_a_usar = $datos_a_usar[0];
  $campo_a_usar = $datos_a_usar[1];
  
  $consulta_seleccion = 'SELECT '.$campo_a_usar.'
  FROM '.$tabla_a_usar.'
  WHERE borrado LIKE "no" 
  ORDER BY '.$campo_a_usar.'
  ASC';

  $query_seleccion = $conexion->prepare($consulta_seleccion);
  $query_seleccion->execute();

  while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC))
  {
    
    $series[$i_series] = $rows_seleccion[$campo_a_usar];

    $i_series++;
  }

  natsort($series);

  $valor_inicial = end($series);

  $valor_inicial_letras = substr($valor_inicial, 0, 3);
  $valor_inicial_numeros = substr($valor_inicial, 3);

  $proximo_numero = $valor_inicial_numeros+1;

  if($proximo_numero == 1000)
  {
    $proximo_numero = 1;
    $letra_3 = $valor_inicial_letras[2];
    $letra_2 = $valor_inicial_letras[1];
    $letra_1 = $valor_inicial_letras[0];

    if(strtolower($letra_3) == end($sequencia_de_letras_a))
    {
      $letra_3 = $sequencia_de_letras_a[0];

      if(strtolower($letra_2) == end($sequencia_de_letras_a))
      {
        $letra_2 = $sequencia_de_letras_a[0];
        
        if(strtolower($letra_1) == end($sequencia_de_letras_a))
        {
          $letra_1 = $sequencia_de_letras_a[0];
        }
        else
        {
          $letra_1 = $sequencia_de_letras_a[array_search($letra_1, $sequencia_de_letras_a)+1];
        }

      }
      else
      {
        $letra_2 = $sequencia_de_letras_a[array_search($letra_2, $sequencia_de_letras_a)+1];
      }

    }
    else
    {
      $letra_3 = $sequencia_de_letras_a[array_search($letra_3, $sequencia_de_letras_a)+1];
    }
    
    $valor_final_letras = $letra_1.$letra_2.$letra_3;
    $valor_final = $valor_final_letras.str_pad($proximo_numero, 3, "0", STR_PAD_LEFT);
  }
  else
  {
    $valor_final_letras = $valor_inicial_letras;
    $valor_final = $valor_final_letras.str_pad($proximo_numero, 3, "0", STR_PAD_LEFT);
  }

  $valor = $valor_final;
}

  echo $valor;
  echo '<input type="hidden" name="'.$campo_nombre.'" class="datos" id="armar-codigo" value="'.$valor.'"/>';

?>
