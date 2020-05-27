<?php if(!isset($_SESSION)) {session_start();}
  
  if((isset($_GET['caso']) and $_GET['caso'] == "agregar") or (isset($en_proceso) and $en_proceso == "si"))
  {
        $datos_a_usar = explode("-",$campo_atributo['herramientas']);
        $ultimo_numero = $datos_a_usar[2]."-0000000";
        
        $consulta_seleccion = 'SELECT *
            FROM '.$datos_a_usar[0].'
            WHERE borrado LIKE "no"
            AND '.$datos_a_usar[1].' LIKE "'.$datos_a_usar[2].'%"
            ORDER BY '.$datos_a_usar[1].'
            DESC
            LIMIT 1';
        $query_seleccion = $conexion->prepare($consulta_seleccion);
        $query_seleccion->execute();
        while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC)) $ultimo_numero = $rows_seleccion[$datos_a_usar[1]];
        
        $numero_partes = explode("-", $ultimo_numero);
        $numero_a_usar = $datos_a_usar[2]."-".str_pad($numero_partes[1] + 1, 7, "0", STR_PAD_LEFT);
        
        if(isset($_POST['grabar']))
        {
            $consulta.= trim(strtolower($numero_a_usar))."','";
        }
        else
        {
            echo $numero_a_usar;
            echo '<input type="hidden" name="'.$campo_nombre.'" class="datos" value="'.$numero_a_usar.'"/>';    
        }
    }
    else
    {
        echo '<input type="text" name="'.$campo_nombre.'" class="datos" value="'.$rows[$campo_nombre].'"/>';    
    }
  

?>
