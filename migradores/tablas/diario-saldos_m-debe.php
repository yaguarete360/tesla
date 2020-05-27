<?php if (!isset($_SESSION)) {session_start();}

$tabla_de_origen = "saldos_m";
$tabla_de_destino = "diario";
$campo_disyuntor = "SALDOS_DEB";
$doble_disyuntor = "";

$campos = array(
'id'                => array('metodo' => 'escribir', 'texto' => ''),
'dia'               => array('metodo' => 'mantener', 'campo' => 'SALDOS_VEN'), //SALDOS_FEP
'cuenta'            => array('metodo' => 'importar', 'campo' => 'SALDOS_TIT', 'tabla' => 'CLIENTES_ORACLE', 'clave' => 'CLI_NUMERO', 'calve-tipo' => 'texto', 'dato_1' => 'CLI_APELLIDO', 'dato_2' => 'CLI_NOMBRE', 'separador' => ', '),
'cuenta_tipo'       => array('metodo' => 'escribir', 'texto' => 'clientes'),
'cuenta_numero'     => array('metodo' => 'mantener', 'campo' => 'SALDOS_TIT'),
'contrato'          => array('metodo' => 'contrato', 'linea' => 'SALDOS_SUC', 'centro' => 'SALDOS_PRO', 'numero' => 'SALDOS_NUM'),
'cuota'             => array('metodo' => 'mantener', 'campo' => 'SALDOS_NCU'),
'movimiento_caja'   => array('metodo' => 'mantener', 'campo' => 'SALDOS_MOV'),
'asociacion'        => array('metodo' => 'importar', 'campo' => 'SALDOS_ASO', 'tabla' => 'ASOCIA_M', 'clave' => 'ASOCIA_NUM', 'calve-tipo' => 'numero' ,'dato_1' => 'ASOCIA_NOM'),
'asociacion_numero' => array('metodo' => 'mantener', 'campo' => 'SALDOS_ASO'),
'planilla'          => array('metodo' => 'mantener', 'campo' => 'SALDOS_PL2', 'vacio' => 'no-aplicable'),
'vencimiento'       => array('metodo' => 'mantener', 'campo' => 'SALDOS_VEN'),
'chequera_numero'   => array('metodo' => 'mantener', 'campo' => 'SALDOS_CHE', 'vacio' => 'no-aplicable'),
'chequera_hoja'     => array('metodo' => 'mantener', 'campo' => 'SALDOS_HOJ', 'vacio' => 'no-aplicable'),
'pagare'            => array('metodo' => 'mantener', 'campo' => 'SALDOS_PAG', 'vacio' => 'no-aplicable'),
'pagare_entrega'    => array('metodo' => 'mantener', 'campo' => 'SALDOS_FEN'),
'vendedor'          => array('metodo' => 'escribir', 'texto' => 'sin datos'),
'vendedor_numero'   => array('metodo' => 'escribir', 'texto' => 'sin datos'),
'cobrador_1'        => array('metodo' => 'importar', 'campo' => 'SALDOS_CDC', 'tabla' => 'COBRAD_M', 'clave' => 'COBRAD_NUM', 'calve-tipo' => 'texto', 'dato_1' => 'COBRAD_NOM'),
'cobrador_1_numero' => array('metodo' => 'mantener', 'campo' => 'SALDOS_CDC'),
'cobrador_2'        => array('metodo' => 'importar', 'campo' => 'SALDOS_CAJ', 'tabla' => 'COBRAD_M', 'clave' => 'COBRAD_NUM', 'calve-tipo' => 'texto', 'dato_1' => 'COBRAD_NOM'),
'cobrador_2_numero' => array('metodo' => 'mantener', 'campo' => 'SALDOS_CAJ'),
'cobrador_3'        => array('metodo' => 'importar', 'campo' => 'SALDOS_COB', 'tabla' => 'COBRAD_M', 'clave' => 'COBRAD_NUM', 'calve-tipo' => 'texto', 'dato_1' => 'COBRAD_NOM'),
'cobrador_3_numero' => array('metodo' => 'mantener', 'campo' => 'SALDOS_COB'),
'descripcion'       => array('metodo' => 'escribir', 'texto' => 'sin mas'),
'cantidad'          => array('metodo' => 'escribir', 'texto' => '1'),
'documento_tipo'    => array('metodo' => 'comprobante', 'tipo' => 'SALDOS_TIP', 'serie' => 'SALDOS_SER', 'serie-vieja' => 'SALDOS_SEF', 'recibo' => 'SALDOS_NRE'),
'documento_numero'  => array('metodo' => 'comprobante-numero', 'serie' => 'SALDOS_SER','serie-vieja' => 'SALDOS_SEF', 'numero_factura' => 'SALDOS_FAC', 'numero_recibo' => 'SALDOS_NRE'),
'observacion'       => array('metodo' => 'concatenar', 'dato_1' => 'SALDOS_PDE','dato_2' => 'SALDOS_PHA', 'dato_3' => 'SALDOS_OBD', 'dato_4' => 'SALDOS_OBH', 'dato_5' => '', 'dato_6' => '', 'dato_7' => '', 'dato_8' => '', 'dato_9' => '','separador' => '-*-'),
'modificacion'      => array('metodo' => 'concatenar', 'dato_1' => 'SALDOS_DAT','dato_2' => 'SALDOS_TIM', 'dato_3' => 'SALDOS_FEM', 'dato_4' => 'SALDOS_ADS', 'dato_5' => 'SALDOS_PNA', 'dato_6' => 'SALDOS_PER', 'dato_7' => 'SALDOS_USU', 'dato_8' => 'SALDOS_IMO', 'dato_9' => 'SALDOS_EST','separador' => '-*-'),
'entra'             => array('metodo' => 'escribir', 'texto' => '1'),
'sale'              => array('metodo' => 'escribir', 'texto' => '1'),
'debe'              => array('metodo' => 'mantener', 'campo' => 'SALDOS_DEB'),
'haber'             => array('metodo' => 'escribir', 'texto' => '0'),
'cotizacion'        => array('metodo' => 'escribir', 'texto' => 'sin datos'),
'origen'            => array('metodo' => 'origen', 'tabla_origen' => $tabla_de_origen, 'id' => 'id', 'campo_disyuntor' => $campo_disyuntor, 'doble_disyuntor' => $doble_disyuntor),
'creado'            => array('metodo' => 'mantener', 'campo' => 'SALDOS_VEN'),
'modificado'        => array('metodo' => 'escribir', 'texto' => ''),
'borrado'           => array('metodo' => 'escribir', 'texto' => 'no'),
'borrado_motivo'    => array('metodo' => 'escribir', 'texto' => 'esta vigente'),
'usuario'           => array('metodo' => 'escribir', 'texto' => 'admin.migrado')
);

?>
