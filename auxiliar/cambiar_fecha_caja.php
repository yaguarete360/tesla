<?php
/**
 * Created by PhpStorm.
 * User: jorgedavidolmedo
 * Date: 4/1/19
 * Time: 2:13 PM
 */
include 'funciones/conectar-base-de-datos.php';
$facturas =array("0393634",
                 "0393635",
                 "0393636",
    "0393637",
    "0393638",
    "0393639",
    "0393640",
    "0393641",
    "0393642",
    "0393643",
    "0393644",
    "0393645",
    "0393646",
    "0393647",
    "0393648",
    "0393649");

$fecha_consulta = "2019-05-07";
$fecha_de_cambio = "2019-04-30";

/************MODIFICAR DIARIO**************/
for($int=0;$int<count($facturas);$int++){
    $consulta = "SELECT * FROM `diario` 
                 WHERE factura_numero LIKE '%".$facturas[$int]."%' AND 
                 fecha='".$fecha_consulta."'";
    $query_resultados = $conexion->prepare($consulta);
    $query_resultados->execute();
    $resultado = $query_resultados->fetchAll();
    $precio_minimo = 0;
    foreach ($resultado as $value){
        echo $value['id']." - ".$facturas[$int]." - FACT: ".$value['factura_numero']."<br>";
        $consulta_update = "UPDATE diario 
                            SET fecha = '".$fecha_de_cambio."', 
                            efectuado_fecha = '".$fecha_de_cambio."' 
                            WHERE id=".$value['id'];
        $query_resultados_update = $conexion->prepare($consulta_update);
        $query_resultados_update->execute();
        echo "UPDATED! DIARIO!<br>";
    }
}
    /************MODIFICAR FORMULARIOS**************/
    for($int=0;$int<count($facturas);$int++) {
        $consulta_2 = "SELECT * FROM `formularios` 
                       WHERE formulario_numero LIKE '%" . $facturas[$int] . "%' AND 
                       fecha_de_uso='" . $fecha_consulta . "'";
        $query_resultados_2 = $conexion->prepare($consulta_2);
        $query_resultados_2->execute();
        $resultado_2 = $query_resultados_2->fetchAll();
        $precio_minimo = 0;

        foreach ($resultado_2 as $value_2) {
            $consulta_update_2 = "UPDATE formularios 
                                  SET fecha_de_uso = '" . $fecha_de_cambio . "', 
                                  custodia_3_entrega_fecha = '" . $fecha_de_cambio . "' 
                                  WHERE id=" . $value_2['id'];
            $query_resultados_update_2 = $conexion->prepare($consulta_update_2);
            $query_resultados_update_2->execute();
            echo "UPDATED! FORMULARIO<br>";
        }

    }


?>