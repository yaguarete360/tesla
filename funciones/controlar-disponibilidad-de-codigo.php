<?php if(!isset($_SESSION)) {session_start();}

    $codigoEx = explode("-", $_POST['codigo']);

    $codigoano = substr($codigoEx[2], 0, 4);
    $codigomes = strtolower(substr($codigoEx[2], 4, 2));
    $codigonum = substr($codigoEx[2], 6);
    $control = $codigoano."-".$codigomes;

    $codigos = array();

    $iCdC = 0;
    $consulta_seleccion = 'SELECT codigo
    FROM difuntos
    WHERE borrado = "no" 
    AND fecha LIKE "'.$control.'%"
    ORDER BY id
    ASC'
    ;
    $query_seleccion = $conexion->prepare($consulta_seleccion);
    $query_seleccion->execute();
    while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC))
    {
        $codigos[$iCdC] = strtolower($rows_seleccion['codigo']);
        $iCdC++;
    }

    $codigo_a_usar = $codigoEx[0]."-".strtolower($codigoEx[1])."-".$codigoano.$codigomes."001";
    $codigo_a_revisar = strtolower($_POST['codigo']);
    for ($iCnC=0; $iCnC < 200; $iCnC++)
    {
        if(in_array(strtolower($codigo_a_revisar), $codigos))
        {
            $codigonumN = str_pad($codigonum+1, 3,"0", STR_PAD_LEFT);
            $codigo_nuevo = $codigoEx[0]."-".strtolower($codigoEx[1])."-".$codigoano.$codigomes.$codigonumN;
            $codigonum = substr($codigo_nuevo, 12);
            $codigo_a_revisar = $codigo_nuevo;
        }
        else
        {
            if(strtolower($codigo_a_revisar) != strtolower($_POST['codigo']))
            {
                echo '<br/>';
                echo '<br/>';
                echo "El codigo utilizado (".$_POST['codigo'].") ya se encuentra ocupado por lo tanto se ha grabado con el codigo: ".strtoupper($codigo_nuevo);
                $codigo_a_usar = $codigo_nuevo;
            }
            else
            {
                $codigo_a_usar = $codigo_a_revisar;
            }
            break;
        }
    }
?>