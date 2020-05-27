<?php if (!isset($_SESSION)) {session_start();}

  switch ($campo_nombre)
  {
    case 'difunto':
    case 'nombre_exequial':
    case 'estado_civil':
    case 'difunto_nacionalidad':
    case 'difunto_documento_tipo':
    case 'difunto_documento_numero':
    case 'nacimiento':
    case 'defuncion_fecha':
    case 'defuncion_hora':
    case 'edad':
    case 'causa':
    case 'certificado_defuncion_doctor':
    case 'certificado_defuncion_doctor_numero':
    case 'certificado_defuncion_numero':
      $estilo_del_td = 'style="background-color:#f5e7d6;padding:10px"';
    break;

    case 'titular':
    case 'titular_documento_tipo':
    case 'titular_documento_numero':
    case 'titular_nacionalidad':
    case 'caracter_de':
    case 'documentacion_caracter':
    case 'autorizador':
    case 'autorizador_documento_tipo':
    case 'autorizador_documento_numero':
    case 'autorizador_documentacion_tipo':
    case 'autorizador_documentacion_numero':
    case 'cuenta_numero':
    case 'sdc_numero':
    case 'sds_numero':
    case 'sdt_numero':
    case 'inh_numero':
    case 'exh_numero':
    case 'exu_numero':
    case 'uds_numero':
    case 'direccion_calle':
    case 'direccion_numero':
    case 'direccion_interseccion':
    case 'direccion_barrio':
    case 'direccion_ciudad':
    case 'telefono':
    case 'celular':
      $estilo_del_td = 'style="background-color:#ffd9b3;padding:10px"';
    break;

    case 'numero_papeleria':
    case 'tipo':
    case 'codigo':
    case 'linea':
    case 'producto':
    case 'monto_lista':
      $estilo_del_td = 'style="background-color:#fff5cc;padding:10px"';
    break;

    case 'inicio_fecha':
    case 'inicio_hora':
    case 'fin_fecha':
    case 'fin_hora':
    case 'sucursal':
    case 'crematorio':
    case 'origen':
    case 'cementerio_origen':
    case 'cementerio_destino':
    case 'cementerio_hora':
    case 'capilla':
    case 'motivo_exhumacion':
    case 'traslado_fecha':
    case 'traslado_hora':
      $estilo_del_td = 'style="background-color:#c1f0c1;padding:10px"';
    break;

    case 'urna_serie':
    case 'feretro_serie':
    case 'feretro_modelo':
    case 'feretro_medida':
    case 'feretro_fecha':
    case 'sitio':
    case 'firmas_libro_numero':
    case 'firmas_libro_linea':
    case 'firmas_libro_tamano':
    case 'mortaja_numero':
    case 'mortaja_color':
    case 'mortaja_tipo':
    case 'mortaja_fecha':
      $estilo_del_td = 'style="background-color:#d9b38c;padding:10px;vertical-align:top"';
    break;

    case 'modo':
    case 'contrato_prepago':
    case 'cooperativa_o_asociacion':
      $estilo_del_td = 'style="background-color:#99ccff;padding:10px"';
    break;

    case 'fecha':
    case 'ultimo_usuario':
    case 'alta_fecha':
    case 'baja_fecha':
    case 'baja_motivo':
      $estilo_del_td = 'style="background-color:#d9d9d9;padding:10px"';
    break;
    
    default:
      $estilo_del_td = "";
    break;
  }

//ROMPE BLOQUES

  switch (strtolower($vista_tipo))
  {
    case 'sepelio':
      switch ($campo_nombre)
      {
        case 'difunto':
        case 'numero_papeleria':
        case 'titular':
        case 'inicio_fecha':
        case 'feretro_serie':
        case 'origen':
        case 'modo':
        case 'pago_de_tramites':
        case 'otorgacion_ultimo_pago':
        case 'alta_fecha':
          $rompe_bloques = "arriba";
        break;

        case 'fecha':
        case 'origen_del_dato':
          $rompe_bloques = "ambos";
        break;

        case 'certificado_defuncion_numero':
        case 'monto_lista':
        case 'autorizador_documentacion_numero':
        case 'celular':
        case 'capilla':
        case 'feretro_fecha':
        case 'firmas_libro_tamano':
        case 'mortaja_fecha':
        case 'cementerio_hora':
        case 'cooperativa_o_asociacion':
        case 'observacion':
        case 'otorgacion_porcentaje':
          $rompe_bloques = "abajo";
        break;

        default:
          $rompe_bloques = "";
        break;
      }
    break;

    case 'cremacion':
      switch ($campo_nombre)
      {
        case 'difunto':
        case 'numero_papeleria':
        case 'titular':
        case 'inicio_fecha':
        case 'feretro_serie':
        case 'origen':
        case 'modo':
        case 'pago_de_tramites':
        case 'otorgacion_ultimo_pago':
        case 'alta_fecha':
          $rompe_bloques = "arriba";
        break;

        case 'fecha':
        case 'origen_del_dato':
        case 'urna_serie':
          $rompe_bloques = "ambos";
        break;

        case 'certificado_defuncion_numero':
        case 'monto_lista':
        case 'autorizador_documentacion_numero':
        case 'celular':
        case 'capilla':
        case 'feretro_fecha':
        case 'firmas_libro_tamano':
        case 'mortaja_fecha':
        case 'cementerio_hora':
        case 'cooperativa_o_asociacion':
        case 'observacion':
        case 'otorgacion_porcentaje':
        case 'sucursal':
        case 'cementerio_origen':
          $rompe_bloques = "abajo";
        break;

        default:
          $rompe_bloques = "";
        break;
      }
    break;

    case 'inhumacion':
      switch ($campo_nombre)
      {
        case 'difunto':
        case 'numero_papeleria':
        case 'titular':
        case 'inicio_fecha':
        case 'feretro_serie':
        case 'origen':
        case 'modo':
        case 'pago_de_tramites':
        case 'otorgacion_ultimo_pago':
        case 'alta_fecha':
          $rompe_bloques = "arriba";
        break;

        case 'fecha':
        case 'origen_del_dato':
          $rompe_bloques = "ambos";
        break;

        case 'certificado_defuncion_numero':
        case 'monto_lista':
        case 'autorizador_documentacion_numero':
        case 'celular':
        case 'capilla':
        case 'feretro_fecha':
        case 'firmas_libro_tamano':
        case 'mortaja_fecha':
        case 'cementerio_hora':
        case 'cooperativa_o_asociacion':
        case 'observacion':
        case 'otorgacion_porcentaje':
        case 'sds_numero':
        case 'sucursal':
        case 'urna_serie':
        case 'sitio':
          $rompe_bloques = "abajo";
        break;

        default:
          $rompe_bloques = "";
        break;
      }
    break;

    case 'exhumacion':
    case 'exunilateral':
      switch ($campo_nombre)
      {
        case 'difunto':
        case 'numero_papeleria':
        case 'titular':
        case 'inicio_fecha':
        case 'feretro_serie':
        case 'origen':
        case 'pago_de_tramites':
        case 'otorgacion_ultimo_pago':
        case 'alta_fecha':
        case 'urna_serie':
          $rompe_bloques = "arriba";
        break;

        case 'fecha':
        case 'origen_del_dato':
        case 'sitio':
        case 'modo':
          $rompe_bloques = "ambos";
        break;

        case 'certificado_defuncion_numero':
        case 'monto_lista':
        case 'autorizador_documentacion_numero':
        case 'celular':
        case 'capilla':
        case 'feretro_fecha':
        case 'firmas_libro_tamano':
        case 'mortaja_fecha':
        case 'cementerio_hora':
        case 'cooperativa_o_asociacion':
        case 'observacion':
        case 'otorgacion_porcentaje':
        case 'sds_numero':
        case 'sucursal':
        case 'urna_serie':
        case 'sitio':
        case 'edad':
          $rompe_bloques = "abajo";
        break;

        default:
          $rompe_bloques = "";
        break;
      }
    break;

    case 'traslado':
      switch ($campo_nombre)
      {
        case 'difunto':
        case 'numero_papeleria':
        case 'titular':
        case 'inicio_fecha':
        case 'feretro_serie':
        case 'origen':
        case 'pago_de_tramites':
        case 'otorgacion_ultimo_pago':
        case 'alta_fecha':
        case 'urna_serie':
          $rompe_bloques = "arriba";
        break;

        case 'fecha':
        case 'origen_del_dato':
        case 'sitio':
        case 'modo':
          $rompe_bloques = "ambos";
        break;

        case 'certificado_defuncion_numero':
        case 'monto_lista':
        case 'autorizador_documentacion_numero':
        case 'celular':
        case 'capilla':
        case 'feretro_fecha':
        case 'firmas_libro_tamano':
        case 'mortaja_fecha':
        case 'traslado_hora':
        case 'cooperativa_o_asociacion':
        case 'observacion':
        case 'otorgacion_porcentaje':
        case 'sds_numero':
        case 'sucursal':
        case 'urna_serie':
        case 'sitio':
        case 'edad':
          $rompe_bloques = "abajo";
        break;

        default:
          $rompe_bloques = "";
        break;
      }
    break;
    
    default:
    break;
  }
  if($campo_atributo['formato'] == "dato-no-aplicable" or $campo_atributo['formato'] == "sin-datos-por-defecto") $rompe_bloques = "";

  //NOMBRES DE CAPITULOS

  $ver_etiqueta = "si";
  $es_nombre_de_capitulo = "no";
  $nombre_del_capitulo = "";

  switch ($campo_nombre)
    {
      case 'feretro_serie':
      case 'urna_serie':
        $es_nombre_de_capitulo = "si";
      break;

      case 'difunto':
      case 'titular':
      case 'sitio':
        $es_nombre_de_capitulo = "si";
        $ver_etiqueta = "no";
      break;

      case 'pago_de_tramites':
        $es_nombre_de_capitulo = "si";
        $nombre_del_capitulo = "Montos y Facturas";
      break;

      case 'numero_papeleria':
      case 'inicio_fecha':
      case 'origen':
        $es_nombre_de_capitulo = "si";
        $nombre_del_capitulo = "Datos del Servicio";
      break;

      case 'modo':
        $es_nombre_de_capitulo = "si";
        $nombre_del_capitulo = "Datos del Contrato";
      break;

      case 'alta_fecha':
        $es_nombre_de_capitulo = "si";
        $nombre_del_capitulo = "Datos del Registro";
      break;

      default:
      break;
    }


?>