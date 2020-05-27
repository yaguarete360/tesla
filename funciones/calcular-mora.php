<?php if (!isset($_SESSION)) {session_start();}

date_default_timezone_set('America/Asuncion');

$edades_beneficiarios = array();
$consulta_venta_producto = 'SELECT beneficiario_edad FROM contratos
    WHERE borrado = "no"
        AND estado = "vigente"
        AND beneficiario_defuncion = "0000-00-00"
        AND contrato LIKE "'.$contrato.'"';
$query_venta_producto = $conexion->prepare($consulta_venta_producto);
$query_venta_producto->execute();
while($rows_venta_producto = $query_venta_producto->fetch(PDO::FETCH_ASSOC))
{
    $edades_beneficiarios[] = $rows_venta_producto['beneficiario_edad'];
}

$fecha_para_calculo_de_mora = $datos['cuota_vencimiento'];
if($a_pagar_de_la_cuota > 0 and $a_pagar_de_la_cuota != $datos['derecho']) $fecha_para_calculo_de_mora = $datos['efectuado_fecha'];

$mora_de_la_cuota = "";
$fecha_base_de_calculo = (isset($_POST['fecha_de_calculo_de_mora']) and !empty($_POST['fecha_de_calculo_de_mora'])) ? $_POST['fecha_de_calculo_de_mora'] : date('Y-m-d');
if($a_pagar_de_la_cuota == 0) 
{
    $fecha_base_de_calculo = $datos['efectuado_fecha'];
    // echo 'efectuado_fecha='.$datos['efectuado_fecha'].'<br/>';
}
// echo 'xyz'.$centro_del_contrato.'<br/>';
switch (strtolower($centro_del_contrato))
{
    case 'cms':
        switch ($_SESSION['forma_de_pago_del_proceso'])
        {
             case 'sin_mora':
             break;

             default:
                // $ano_vencimiento = explode("-", $fecha_para_calculo_de_mora)[0];
                // $ano_en_curso = date('Y');
                // if($ano_vencimiento == $ano_en_curso)
                // {
                //     $dias_de_atraso = (strtotime($fecha_base_de_calculo) - strtotime($fecha_para_calculo_de_mora)) / 86400;
                //     $dias_de_atraso_min = 30;
                //     // echo 'dias_de_atraso: '.$dias_de_atraso.' >= dias_de_atraso_min: '.$dias_de_atraso_min.'<br/>';
                //     if($dias_de_atraso >= $dias_de_atraso_min)
                //     {
                //         $porcentaje_mora = 10 / 100;
                //         // $monto_mora = $datos['derecho'] * $porcentaje_mora;
                //         $monto_mora = $a_pagar_de_la_cuota * $porcentaje_mora;
                //         // echo 'xyz'.$a_pagar_de_la_cuota;
                //     }
                // }
                // echo '1: '.$fecha_base_de_calculo.' 2: '.$fecha_para_calculo_de_mora.'<br/>';
                $dias_de_atraso = floor((strtotime($fecha_base_de_calculo) - strtotime($fecha_para_calculo_de_mora)) / 86400);
                $dias_de_atraso_min = 30;
                if($dias_de_atraso >= $dias_de_atraso_min)
                {
                    $porcentaje_mora = 0.0005;
                    $monto_mora = $a_pagar_de_la_cuota * $porcentaje_mora * $dias_de_atraso;
                }
                // echo date_default_timezone_get().'<br/>';
                // echo $fecha_base_de_calculo.strtotime($fecha_base_de_calculo).' a '.$fecha_para_calculo_de_mora.strtotime($fecha_para_calculo_de_mora).' dias_de_atraso='.$dias_de_atraso.' -> '.$porcentaje_mora.' * '.number_format($a_pagar_de_la_cuota).'<br/>';
             break;
        }
    break;
        
    case 'psm':

        // ninguno de los beneficiarios mayor de 40 para cobrar ???
        if(!isset($tiene_perdida_de_vigencia)) $tiene_perdida_de_vigencia = array();
        if(!isset($tiene_perdida_de_vigencia[$contrato])) $tiene_perdida_de_vigencia[$contrato] = 'no';

        $edad_maxima = 40;

        $tiene_mayores_de_40 = "no";
        if(!isset($edades_beneficiarios) or empty($edades_beneficiarios)) $edades_beneficiarios = array(0);
        if(max($edades_beneficiarios) > $edad_maxima) $tiene_mayores_de_40 = "si";

        $dias_de_atraso = (strtotime($fecha_base_de_calculo) - strtotime($fecha_para_calculo_de_mora)) / 86400;
        if($tiene_mayores_de_40 == "si" and $dias_de_atraso > 31)
        {
            $mora_de_la_cuota = "perdida de vigencia";
            $monto_mora = 0;
            $tiene_perdida_de_vigencia[$contrato] = 'si';
        }
        else
        {
            switch ($_SESSION['forma_de_pago_del_proceso'])
            {
                case 'cobrador_a_domicilio':
                    $fecha_de_atraso_min = date("Y-m", strtotime(substr($fecha_para_calculo_de_mora, 0, -3)." +1 months")).'-01';
                    // echo 'fecha_base_de_calculo = '.$fecha_base_de_calculo.'<br/>';
                    // echo 'ENTRA1: '.$fecha_para_calculo_de_mora.' < '.$fecha_de_atraso_min.' and '.$tiene_perdida_de_vigencia[$contrato].' == "no"<br/>';
                    // echo $fecha_para_calculo_de_mora.'dias_de_atraso='.$dias_de_atraso.' --- '.$fecha_de_atraso_min.'<br/>';
                    // if($fecha_base_de_calculo > $fecha_de_atraso_min)
                    // $fecha_de_atraso_min = 1;
                    if($fecha_de_atraso_min < $fecha_base_de_calculo and $tiene_perdida_de_vigencia[$contrato] == 'no')
                    {
                        $porcentaje_mora = 10 / 100;
                        // $monto_mora = $datos['derecho'] * $porcentaje_mora;
                        $monto_mora = $a_pagar_de_la_cuota * $porcentaje_mora;
                    }
                break;

                case 'asociacion': case 'debito_automatico': case 'cobranza_externa': case 'sin_mora':
                    // asociacion, debito automatico o boca de cobranza externa
                    // NO HAY RECARGO (???)
                break;
                
                case 'en_ventanilla':
                default:
                    // no es cobrador a domicilio ni asociacion ni debito automatico
                    $dias_de_atraso = (strtotime($fecha_base_de_calculo) - strtotime($fecha_para_calculo_de_mora)) / 86400;
                    $dias_de_atraso_min = 1;
                    if($dias_de_atraso > $dias_de_atraso_min and $tiene_perdida_de_vigencia[$contrato] == 'no')
                    {
                        $porcentaje_mora = 10 / 100;
                        // $monto_mora = $datos['derecho'] * $porcentaje_mora;
                        $monto_mora = $a_pagar_de_la_cuota * $porcentaje_mora;
                    }
                break;
            }
        }
    break;

    case 'sds':
    case 'sdc':
    case 'sdt':
    case 'inh':
    case 'exh':
    case 'exu':
        $monto_mora = 0;
    break;
        
    default: //  UDS, PSV, PSC, PSI, PMV  //  SDS, SDC, SDT, INH, EXH
        switch ($_SESSION['forma_de_pago_del_proceso'])
        {
            // case 'servicios':
            // case 'cobrador_a_domicilio':
            //     // echo 'ENTRA2: '.$fecha_base_de_calculo.' >= '.$ultimo_dia_mes_vencimiento.'<br/>';
            //     $ultimo_dia_mes_vencimiento = date('Y-m-t', strtotime($fecha_para_calculo_de_mora));
            //     if($fecha_base_de_calculo >= $ultimo_dia_mes_vencimiento)
            //     {
            //         // $dias_de_atraso = (strtotime($ultimo_dia_mes_vencimiento) - strtotime($fecha_para_calculo_de_mora)) / 86400;
            //         $dias_de_atraso = (strtotime($fecha_base_de_calculo) - strtotime($fecha_para_calculo_de_mora)) / 86400;
            //         $meses_de_atraso = floor($dias_de_atraso / 30);
            //         // $dias_de_atraso_min = 1;
            //         $porcentaje_mora = 0.03 * $meses_de_atraso;

            //         // $ultimo_dia_mes_anterior = date('Y-m-t', strtotime(date('Y-m').'-01 -1 day'));
            //         // $dias_de_atraso+= (strtotime($ultimo_dia_mes_anterior) - strtotime($ultimo_dia_mes_vencimiento)) / 86400;
            //         // $monto_mora = $datos['derecho'] * $dias_de_atraso * $porcentaje_mora;
            //         $monto_mora = $a_pagar_de_la_cuota * $porcentaje_mora;
            //     }
            // break;

            case 'chequera_vision_banco':
                $dias_de_atraso_min = 1;
                $porcentaje_mora = 3 / 100;
                $dias_de_atraso = (strtotime($fecha_base_de_calculo) - strtotime($fecha_para_calculo_de_mora)) / 86400;
                // if($dias_de_atraso >= $dias_de_atraso_min) $monto_mora = $datos['derecho'] * $porcentaje_mora;
                if($dias_de_atraso >= $dias_de_atraso_min) $monto_mora = $a_pagar_de_la_cuota * $porcentaje_mora;
            break;

            case 'sin_mora':
            break;
            
            default:
                $dias_de_atraso_min = 1;
                $porcentaje_mora = 0.1 / 100;
                $dias_de_atraso = (strtotime($fecha_base_de_calculo) - strtotime($fecha_para_calculo_de_mora)) / 86400;

                // if($dias_de_atraso >= $dias_de_atraso_min) $monto_mora = $datos['derecho'] * $dias_de_atraso * $porcentaje_mora;
                if($dias_de_atraso >= $dias_de_atraso_min) $monto_mora = $a_pagar_de_la_cuota * round($dias_de_atraso) * $porcentaje_mora;
            break;
        }
    break;
}
// echo 'forma_de_pago_del_proceso='.$_SESSION['forma_de_pago_del_proceso'];
if(isset($monto_mora)) $monto_mora = round($monto_mora);

?>

