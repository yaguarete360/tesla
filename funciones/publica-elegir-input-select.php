<?php if (!isset($_SESSION)) {session_start();}

if(!isset($devolver_como_get) and isset($_GET[$variable])) $_SESSION[$variable] = $_GET[$variable];
if(isset($_GET["empresa"])) unset($_SESSION["ejercicio"]);

if($obligatorio) $obligatorio = "required";

echo '<select class="elegir" name="'.$variable.'" value="'.$_SESSION[$variable].'" '.$obligatorio.'>';
    if(isset($_SESSION[$variable]))
    {
        echo '<option value="'.$_SESSION[$variable].'" selected>'.ucwords(str_replace("_"," ",$_SESSION[$variable])).'</option>';
    }
    
    if(isset($opcion_en_blanco) and $opcion_en_blanco == "si") echo '<option value=""></option>';
    if($opcion_no_aplicable) echo '<option value="no_aplicable">no_aplicable</option>';
    
    $cn_select = 'SELECT '.$campos_selectados.' ';
    $cn_select.= 'FROM '.$tabla.' ';
    $cn_select.= 'WHERE borrado = "no" ';
    if(!empty($campo_donde_1))          $cn_select.= 'AND '.$campo_donde_1.' = "'.trim($valor_donde_1).'" ';
    if(!empty($campo_donde_no_igual))   $cn_select.= 'AND '.$campo_donde_no_igual.' != "'.trim($valor_donde_no_igual).'" ';
    if(!empty($campo_donde_2))          $cn_select.= 'AND '.$campo_donde_2.' = "'.trim($valor_donde_2).'" ';
    if(!empty($campo_donde_3))          $cn_select.= 'AND '.$campo_donde_3.' = "'.trim($valor_donde_3).'" ';
    if(!empty($agrupado_por))           $cn_select.= 'GROUP BY '.$agrupado_por.' ';                
    if(!empty($ordenado_por))           $cn_select.= 'ORDER BY '.$ordenado_por.' ASC';
    $cn_select.= ';';
    
    $qy_select = $conexion->prepare($cn_select);
    $qy_select->execute();
    

    while($rw_select = $qy_select->fetch(PDO::FETCH_ASSOC))
    {
        if(!empty($listado_por_2))
        {    
            echo '<option value="'.$rw_select[$listado_por_1].'">'.ucwords(str_replace("_"," ",$rw_select[$listado_por_1])).': '.ucwords(str_replace("_"," ",$rw_select[$listado_por_2])).'</option>';
        }
        else
        {
            echo '<option value="'.$rw_select[$listado_por_1].'">'.ucwords(str_replace("_"," ",$rw_select[$listado_por_1])).'</option>';
        }
    }
echo '</select>';

?>
