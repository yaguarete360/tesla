<?php if (!isset($_SESSION)) {session_start();}

$tabla_de_origen = "operaciones_funcionarios";
$tabla_de_destino = "diario";
$campo_disyuntor = "";
$doble_disyuntor = "";

$campos = array(
'id'                => array('metodo' => 'escribir', 'texto' => ''),
'dia'               => array('metodo' => 'mantener', 'campo' => 'fecha'),
'cuenta'            => array('metodo' => 'invertir-nombre', 'campo' => 'funcionario'),
'cuenta_tipo'       => array('metodo' => 'escribir', 'texto' => 'funcionarios'),
'cuenta_numero'     => array('metodo' => 'mantener', 'campo' => 'id'),
'contrato'          => array('metodo' => 'escribir', 'texto' => 'dependiente'),
'cuota'             => array('metodo' => 'escribir', 'texto' => 'sin datos'),
'movimiento_caja'   => array('metodo' => 'escribir', 'texto' => ''),
'asociacion'        => array('metodo' => 'escribir', 'texto' => 'no aplicable'),
'asociacion_numero' => array('metodo' => 'escribir', 'texto' => ''),
'planilla'          => array('metodo' => 'mantener', 'campo' => 'descripcion'),
'vencimiento'       => array('metodo' => 'mantener', 'campo' => 'fecha'),
'chequera_numero'   => array('metodo' => 'escribir', 'texto' => 'no aplicable'),
'chequera_hoja'     => array('metodo' => 'escribir', 'texto' => 'no aplicable'),
'pagare'            => array('metodo' => 'escribir', 'texto' => 'no aplicable'),
'pagare_entrega'    => array('metodo' => 'escribir', 'texto' => ''),
'vendedor'          => array('metodo' => 'escribir', 'texto' => 'no aplicable'),
'vendedor_numero'   => array('metodo' => 'escribir', 'texto' => ''),
'cobrador_1'        => array('metodo' => 'escribir', 'texto' => 'no aplicable'),
'cobrador_1_numero' => array('metodo' => 'escribir', 'texto' => ''),
'cobrador_2'        => array('metodo' => 'escribir', 'texto' => 'no aplicable'),
'cobrador_2_numero' => array('metodo' => 'escribir', 'texto' => ''),
'cobrador_3'        => array('metodo' => 'escribir', 'texto' => 'no aplicable'),
'cobrador_3_numero' => array('metodo' => 'escribir', 'texto' => ''),
'descripcion'       => array('metodo' => 'mantener', 'campo' => 'documento_tipo'),
'cantidad'          => array('metodo' => 'escribir', 'texto' => '1'),
'documento_tipo'    => array('metodo' => 'escribir', 'texto' => 'itau online'),
'documento_numero'  => array('metodo' => 'mantener', 'campo' => 'documento_numero'),
'observacion'       => array('metodo' => 'escribir', 'texto' => 'sin mas'),
'modificacion'      => array('metodo' => 'escribir', 'texto' => 'sin mas'),
'entra'             => array('metodo' => 'escribir', 'texto' => '1'),
'sale'              => array('metodo' => 'escribir', 'texto' => '1'),
'debe'              => array('metodo' => 'mantener', 'campo' => 'debe'),
'haber'             => array('metodo' => 'mantener', 'campo' => 'haber'),
'cotizacion'        => array('metodo' => 'mantener', 'campo' => 'cotizacion'),
'origen'            => array('metodo' => 'origen', 'tabla_origen' => $tabla_de_origen, 'id' => 'id', 'campo_disyuntor' => $campo_disyuntor, 'doble_disyuntor' => $doble_disyuntor),
'creado'            => array('metodo' => 'mantener', 'campo' => 'alta_fecha'),
'modificado'        => array('metodo' => 'escribir', 'texto' => 'modificado'),
'borrado'           => array('metodo' => 'escribir', 'texto' => 'no'),
'borrado_motivo'    => array('metodo' => 'escribir', 'texto' => 'esta vigente'),
'usuario'           => array('metodo' => 'escribir', 'texto' => 'admin.migrado')
);

?>
