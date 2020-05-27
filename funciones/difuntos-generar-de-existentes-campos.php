<?php if(!isset($_SESSION)) {session_start();}

$campos_a_no_incluir_s = "busqueda_funcionario,busqueda_vehiculo,
    busqueda_tiempo,
    laboratorio_funcionario,
    laboratorio_tiempo,
    traslado_funcionario,
    traslado_vehiculo,
    traslado_tiempo,
    cantina_funcionario_t1,
    cantina_funcionario_t2,
    limpieza_funcionario_t1,
    limpieza_funcionario_t2,
    tramite_funcionario,
    tramite_vehiculo,
    tramite_tiempo,
    responso_cura,
    responso_hora,
    soldadura_funcionario,
    soldadura_hora,
    cortejo_funcionario_v1,
    cortejo_vehiculo_v1,
    cortejo_funcionario_v2,
    cortejo_vehiculo_v2,
    cortejo_funcionario_v3,
    cortejo_vehiculo_v3,
    cortejo_funcionario_v4,
    cortejo_vehiculo_v4,
    comentario,
    oculto,
    concluido";
$campos_a_no_incluir_a = explode(",", $campos_a_no_incluir_s);

foreach ($campos_a_no_incluir_a as $i_cani => $campo_a_no_incluir)
{
    $campos_a_no_incluir_F[$i_cani] = trim($campo_a_no_incluir);
}

$campos_a_recargar_s = "tipo,
    numero_papeleria,
    codigo,
    linea,
    producto,
    monto_lista,
    inicio_fecha,
    inicio_hora,
    fin_fecha,
    fin_hora,
    sucursal,
    origen,
    crematorio,
    modo,
    contrato_prepago,
    cooperativa_o_asociacion,
    monto_diferido,
    monto_particular,
    monto_adicionales,
    descripcion_adicionales,
    cobertura_otorgada,
    observacion,
    alta_fecha,
    baja_fecha,
    baja_motivo,
    ultimo_usuario";
$campos_a_recargar_a = explode(",", $campos_a_recargar_s);

foreach ($campos_a_recargar_a as $i_car => $campo_a_recargar)
{
    $campos_a_recargar_F[$i_car] = trim($campo_a_recargar);
}

foreach($campos as $campo_nombre => $campo_atributo)
{
    if(!in_array($campo_nombre, $campos_a_no_incluir_F))
    {
        switch ($prestacion_nueva_siglas)
        {
            case 'SDS':
                $prestacion_nueva_comp = "Sepelio";
                
                $campos_no_aplicables_s = "cementerio_origen,
                    crematorio,
                    sitio,
                    motivo_exhumacion,
                    uds_numero,
                    inh_numero,
                    exh_numero,
                    exu_numero,
                    sdc_numero,
                    otd_numero,
                    sdt_numero,
                    traslado_fecha,
                    traslado_hora,
                    otorgacion_centro,
                    otorgacion_producto,
                    otorgacion_vigencia_fecha";
            break;

            case 'SDC':
                $prestacion_nueva_comp = "Cremacion";

                $campos_no_aplicables_s = "capilla,
                    cementerio_hora,
                    cementerio_destino,
                    firmas_libro_numero,
                    firmas_libro_linea,
                    firmas_libro_tamano,
                    sitio,feretro_serie,
                    feretro_modelo,
                    feretro_medida,
                    feretro_fecha,
                    mortaja_numero,
                    mortaja_color,
                    mortaja_tipo,
                    mortaja_fecha,
                    uds_numero,
                    inh_numero,
                    exh_numero,
                    exu_numero,
                    sds_numero,
                    otd_numero,
                    traslado_fecha,
                    traslado_hora,
                    pago_de_tramites,
                    pago_de_transporte,
                    monto_psm,
                    monto_apertura_fosa,
                    monto_por_cms,
                    monto_por_placa,
                    sdt_numero,
                    motivo_exhumacion,
                    pagare_cooperativa_numero,
                    pagare_cooperativa_monto,
                    otorgacion_centro,
                    otorgacion_producto,
                    otorgacion_vigencia_fecha";

            break;

            case 'INH':
                $prestacion_nueva_comp = "Inhumacion";

                $campos_no_aplicables_s = "capilla,
                    crematorio,
                    cementerio_origen,
                    firmas_libro_numero,
                    firmas_libro_linea,
                    firmas_libro_tamano,
                    mortaja_numero,
                    mortaja_color,
                    mortaja_tipo,
                    mortaja_fecha,
                    exh_numero,
                    exu_numero,
                    sdc_numero,
                    sdt_numero,
                    otd_numero,
                    traslado_fecha,
                    traslado_hora,
                    pago_de_tramites,
                    pago_de_transporte,
                    monto_psm,
                    monto_psv,
                    cobertura_otorgada,
                    guardar_pertenencias,
                    motivo_exhumacion,
                    pagare_cooperativa_numero,
                    pagare_cooperativa_monto,
                    otorgacion_centro,
                    otorgacion_producto,
                    otorgacion_vigencia_fecha,
                    otorgacion_ultimo_pago,
                    otorgacion_cuota,
                    otorgacion_factura,
                    otorgacion_recibo,
                    otorgacion_mes_ano,
                    otorgacion_porcentaje";

            break;

            case 'SDT':
                $prestacion_nueva_comp = "Traslado";

                $campos_no_aplicables_s = "capilla,
                    crematorio,
                    firmas_libro_numero,
                    firmas_libro_linea,
                    firmas_libro_tamano,
                    defuncion_hora,
                    motivo_exhumacion,
                    causa,
                    certificado_defuncion_doctor,
                    certificado_defuncion_doctor_numero,
                    certificado_defuncion_numero,
                    feretro_vineta,
                    feretro_serie,
                    feretro_modelo,
                    feretro_medida,
                    feretro_fecha,
                    mortaja_numero,
                    mortaja_color,
                    mortaja_tipo,
                    mortaja_fecha,
                    inh_numero,
                    exh_numero,
                    exu_numero,
                    sds_numero,
                    otd_numero,
                    contrato_prepago,
                    cooperativa_o_asociacion,
                    monto_particular,
                    monto_psm,
                    monto_psv,
                    pagare_cooperativa_numero,
                    pagare_cooperativa_monto,
                    cobertura_otorgada,
                    guardar_pertenencias,
                    otorgacion_centro,
                    otorgacion_producto,
                    otorgacion_vigencia_fecha,
                    otorgacion_ultimo_pago,
                    otorgacion_cuota,
                    otorgacion_factura,
                    otorgacion_recibo,
                    otorgacion_mes_ano,
                    otorgacion_porcentaje";
                    // modo,

            break;

            case 'EXH':
            $prestacion_nueva_comp = "Exhumacion";

            $campos_no_aplicables_s = "capilla,
                crematorio,
                firmas_libro_numero,
                firmas_libro_linea,
                firmas_libro_tamano,
                causa,
                certificado_defuncion_doctor,
                certificado_defuncion_doctor_numero,
                certificado_defuncion_numero,
                feretro_vineta,
                feretro_serie,
                feretro_modelo,
                feretro_medida,
                feretro_fecha,
                mortaja_numero,
                mortaja_color,
                mortaja_tipo,
                mortaja_fecha,
                exu_numero,
                sdc_numero,
                sds_numero,
                otd_numero,
                traslado_fecha,
                traslado_hora,
                pago_de_tramites,
                pago_de_transporte,
                contrato_prepago,
                cooperativa_o_asociacion,
                monto_particular,
                monto_psm,
                monto_psv,
                cobertura_otorgada,
                guardar_pertenencias,
                pagare_cooperativa_numero,
                pagare_cooperativa_monto,
                otorgacion_centro,
                otorgacion_producto,
                otorgacion_vigencia_fecha,
                otorgacion_ultimo_pago,
                otorgacion_cuota,
                otorgacion_factura,
                otorgacion_recibo,
                otorgacion_mes_ano,
                otorgacion_porcentaje";
                // modo,

            break;

            case 'EXU':
            $prestacion_nueva_comp = "Exunilateral";

            $campos_no_aplicables_s = "capilla,
                crematorio,
                firmas_libro_numero,
                firmas_libro_linea,
                firmas_libro_tamano,
                causa,
                certificado_defuncion_doctor,
                certificado_defuncion_doctor_numero,
                certificado_defuncion_numero,
                feretro_vineta,
                feretro_serie,
                feretro_modelo,
                feretro_medida,
                feretro_fecha,
                mortaja_numero,
                mortaja_color,
                mortaja_tipo,
                mortaja_fecha,
                exh_numero,
                sdc_numero,
                sds_numero,
                otd_numero,
                traslado_fecha,
                traslado_hora,
                pago_de_tramites,
                pago_de_transporte,
                contrato_prepago,
                cooperativa_o_asociacion,
                monto_particular,
                monto_psm,
                monto_psv,
                cobertura_otorgada,
                guardar_pertenencias,
                pagare_cooperativa_numero,
                pagare_cooperativa_monto,
                otorgacion_centro,
                otorgacion_producto,
                otorgacion_vigencia_fecha,
                otorgacion_ultimo_pago,
                otorgacion_cuota,
                otorgacion_factura,
                otorgacion_recibo,
                otorgacion_mes_ano,
                otorgacion_porcentaje";
                // modo,

            break;

            default:
            $prestacion_nueva_comp = "ERROR";
            $campos_a_recargar_s = "";
            break;
        }

        $campos_no_aplicables_a = explode(",", $campos_no_aplicables_s);
        foreach ($campos_no_aplicables_a as $i_cna => $campo_no_aplicables)
        {
            $campos_no_aplicables_F[$i_cna] = trim($campo_no_aplicables);
        }

        if(in_array($campo_nombre, $campos_no_aplicables_F))
        {
            $campo_atributo['formato'] = "dato-no-aplicable";
        }

        $campos_finales[$campo_nombre] = $campo_atributo;

    }
}

?>
