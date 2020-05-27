<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('../tcpdf/tcpdf.php');
include_once("../PHPJasperXML.inc.php");
include_once ('../setting_2.php');
include_once ('../../../../funciones/conectar-base-de-datos.php');

//$_SESSION['set_cuenta'] = "cueto laschi, julio benigno";
$cuenta = $_SESSION['set_cuenta'];

    $sql="select * from ((SELECT
     a.contrato as contrato_cuota,
    a.efectuado_fecha as efectuado_fecha_cuota,
    a.derecho as psm,
    a.cuota_vencimiento as vendedor,
    b.contrato as contrato_cobranza,
    b.documento_tipo as contrato,
    b.efectuado_fecha as efectuado_fecha_cobranza,
    b.obligacion as uds,
    b.cuota_vencimiento as vendedor2,c.contrato as contrato_mora,
    c.efectuado_fecha as efectuado_fecha_mora,
    c.obligacion as psv,
    c.cuota_vencimiento,
    CONCAT(if(MONTH(c.cuota_vencimiento)=1,'ENE',
          if(MONTH(c.cuota_vencimiento)=2,'FEB',
          if(MONTH(c.cuota_vencimiento)=3,'MAR',
          if(MONTH(c.cuota_vencimiento)=4,'ABR',
          if(MONTH(c.cuota_vencimiento)=5,'MAY',
          if(MONTH(c.cuota_vencimiento)=6,'JUN',
          if(MONTH(c.cuota_vencimiento)=7,'JUL',
          if(MONTH(c.cuota_vencimiento)=8,'AGO',
          if(MONTH(c.cuota_vencimiento)=9,'SEP',
          if(MONTH(c.cuota_vencimiento)=10,'OCT',
          if(MONTH(c.cuota_vencimiento)=11,'NOV',
          if(MONTH(c.cuota_vencimiento)=12,'DIC',
          '')))))))))))),'-',YEAR(c.cuota_vencimiento)) as field2,
          CONCAT(0) as saldo,
          CONCAT(0) as recargo,
          a.factura_numero as psc,
          a.cuenta as cuenta,
          a.cuenta_numero as cuenta_nro,
          a.contrato as contrato_nro
    FROM `diario` as a, diario as b, diario as c
    WHERE a.borrado LIKE 'no' AND b.borrado like 'no' AND c.borrado like 'no' and a.cuenta LIKE '".$_SESSION['set_cuenta']."'
    and b.cuenta LIKE '".$_SESSION['set_cuenta']."' and c.cuenta LIKE '".$_SESSION['set_cuenta']."'
    and a.contrato not like '%-var-%' and b.contrato not like '%-var-%' and c.contrato not like '%-var-%'
    and a.efectuado_fecha = b.efectuado_fecha and a.efectuado_fecha = c.efectuado_fecha and a.contrato = b.contrato and a.contrato = c.contrato
    and a.derecho > 0 and b.obligacion > 0  and c.obligacion = 0 and a.efectuado_fecha not in(SELECT a.efectuado_fecha FROM `diario` as a, diario as b, diario as c
    WHERE a.borrado LIKE 'no' AND b.borrado like 'no' AND c.borrado like 'no' and a.cuenta LIKE '".$_SESSION['set_cuenta']."'
    and b.cuenta LIKE '".$_SESSION['set_cuenta']."' and c.cuenta LIKE '".$_SESSION['set_cuenta']."'
    and a.contrato not like '%-var-%' and b.contrato not like '%-var-%' and c.contrato not like '%-var-%'
    and a.efectuado_fecha = b.efectuado_fecha and a.efectuado_fecha = c.efectuado_fecha and a.contrato = b.contrato and a.contrato = c.contrato
    and a.derecho > 0 and b.obligacion > 0  and c.obligacion > 0 and c.obligacion < b.obligacion
    group by a.efectuado_fecha
    order by a.cuota_vencimiento asc)
    group by a.efectuado_fecha,a.contrato
    order by a.documento_tipo,a.cuota_vencimiento asc)
    union
    (SELECT
    a.contrato as contrato_cuota,
    a.efectuado_fecha as efectuado_fecha_cuota,
    a.derecho as psm,
    a.cuota_vencimiento as vendedor,
    b.contrato as contrato_cobranza,
    b.documento_tipo as contrato,
    b.efectuado_fecha as efectuado_fecha_cobranza,
    b.obligacion as uds,
    b.cuota_vencimiento as vendedor2,c.contrato as contrato_mora,
    c.efectuado_fecha as efectuado_fecha_mora,
    c.obligacion as psv,
    c.cuota_vencimiento,
    CONCAT(if(MONTH(c.cuota_vencimiento)=1,'ENE',
          if(MONTH(c.cuota_vencimiento)=2,'FEB',
          if(MONTH(c.cuota_vencimiento)=3,'MAR',
          if(MONTH(c.cuota_vencimiento)=4,'ABR',
          if(MONTH(c.cuota_vencimiento)=5,'MAY',
          if(MONTH(c.cuota_vencimiento)=6,'JUN',
          if(MONTH(c.cuota_vencimiento)=7,'JUL',
          if(MONTH(c.cuota_vencimiento)=8,'AGO',
          if(MONTH(c.cuota_vencimiento)=9,'SEP',
          if(MONTH(c.cuota_vencimiento)=10,'OCT',
          if(MONTH(c.cuota_vencimiento)=11,'NOV',
          if(MONTH(c.cuota_vencimiento)=12,'DIC',
          '')))))))))))),'-',YEAR(c.cuota_vencimiento)) as field2,
          CONCAT(0) as saldo,
          CONCAT(0) as recargo,
          a.factura_numero as psc,
          a.cuenta as cuenta,
          a.cuenta_numero as cuenta_nro,
          a.contrato as contrato_nro
    FROM `diario` as a, diario as b, diario as c
    WHERE a.borrado LIKE 'no' AND b.borrado like 'no' AND c.borrado like 'no' and a.cuenta LIKE '".$_SESSION['set_cuenta']."'
    and b.cuenta LIKE '".$_SESSION['set_cuenta']."' and c.cuenta LIKE '".$_SESSION['set_cuenta']."'
    and a.contrato not like '%-var-%' and b.contrato not like '%-var-%' and c.contrato not like '%-var-%'
    and a.efectuado_fecha = b.efectuado_fecha and a.efectuado_fecha = c.efectuado_fecha and a.contrato = b.contrato and a.contrato = c.contrato
    and a.derecho > 0 and b.obligacion > 0  and c.obligacion > 0 and c.obligacion < b.obligacion
    group by a.efectuado_fecha
    order by a.documento_tipo,a.cuota_vencimiento asc)
    UNION
    (SELECT
     a.contrato as contrato_cuota,
    a.efectuado_fecha as efectuado_fecha_cuota,
    a.derecho as psm,
    a.cuota_vencimiento as vendedor,
    a.contrato as contrato_cobranza,
    a.documento_tipo as contrato,
    a.efectuado_fecha as efectuado_fecha_cobranza,
    a.obligacion as uds,
    a.cuota_vencimiento as vendedor2,a.contrato as contrato_mora,
    a.efectuado_fecha as efectuado_fecha_mora,
    a.obligacion as psv,
    a.cuota_vencimiento,
    CONCAT(if(MONTH(a.cuota_vencimiento)=1,'ENE',
          if(MONTH(a.cuota_vencimiento)=2,'FEB',
          if(MONTH(a.cuota_vencimiento)=3,'MAR',
          if(MONTH(a.cuota_vencimiento)=4,'ABR',
          if(MONTH(a.cuota_vencimiento)=5,'MAY',
          if(MONTH(a.cuota_vencimiento)=6,'JUN',
          if(MONTH(a.cuota_vencimiento)=7,'JUL',
          if(MONTH(a.cuota_vencimiento)=8,'AGO',
          if(MONTH(a.cuota_vencimiento)=9,'SEP',
          if(MONTH(a.cuota_vencimiento)=10,'OCT',
          if(MONTH(a.cuota_vencimiento)=11,'NOV',
          if(MONTH(a.cuota_vencimiento)=12,'DIC',
          '')))))))))))),'-',YEAR(a.cuota_vencimiento)) as field2,
          CONCAT(0) as saldo,
          CONCAT(0) as recargo,
          a.factura_numero as psc,
          a.cuenta as cuenta,
          a.cuenta_numero as cuenta_nro,
           a.contrato as contrato_nro
    FROM `diario` as a
    WHERE a.borrado LIKE 'no'  and a.cuenta LIKE '".$_SESSION['set_cuenta']."'
    and a.contrato not like '%-var-%'
    and a.efectuado_fecha = '0000-00-00'  
    and a.derecho > 0 and 
    a.descripcion like 'cuota%'
    order by a.documento_tipo,a.cuota_vencimiento asc)) as t
    order by t.contrato,t.vendedor asc";

$query_resultados = $conexion->prepare($sql);
$query_resultados->execute();
$res = $query_resultados->fetchAll();

$parameter1 = "";
$parameter2 = "";
$parameter3 = "";
$parameter4 = "";
$parameter5 = "";
$parameter6 = "";
$parameter7 = "";
$parameter8 = "";

$conexion->exec('USE capitulos');
$sql_borrar = "DELETE FROM estado_de_cuenta_tmp WHERE usuario='".$_SESSION['usuario_en_sesion']."'";
$borrar = $conexion->prepare($sql_borrar);
$borrar->execute();
foreach ($res as $value) {
    $saldo = $value['psm'] - $value['uds'];
     $sql_insert = "INSERT INTO `estado_de_cuenta_tmp` (`id`, `periodo`, `vence`, `debe`, 
    `haber`, `saldo`, `pago`, `interes`, `factura`, `cob`, `obs`, `usuario`,`contrato`,
    `cuenta`,`cuenta_nro`,`cliente`,`efectuado_fecha_cuota`,`contrato_nro`) 
    VALUES (NULL, '".$value['field2']."', '".$value['vendedor']."', '".$value['psm']."', 
    '".$value['uds']."', '".$saldo."', '0000-00-00', '".$value['psv']."', 
    '".$value['psc']."', 'sin datos', null, '".$_SESSION['usuario_en_sesion']."','".$value['contrato']."',
    '".$value['cuenta']."','".$value['cuenta_nro']."','sin datos','".$value['efectuado_fecha_cuota']."',
    '".$value['contrato_nro']."')";
     //echo $sql_insert;die();
     $prepare = $conexion->prepare($sql_insert);
     $prepare->execute();
}

$sql_reporte  = "SELECT periodo as field2,
                 vence as vendedor,
                 debe as psm,
                 haber as uds,
                 saldo as saldo,
                 interes as psv,
                 factura as psc,
                 cob as psi,
                 contrato as contrato,
                 cuenta as cuenta,
                 cuenta_nro as cuenta_nro,
                 cliente as cliente,
                 efectuado_fecha_cuota as pago,
                 contrato_nro as contrato_nro
                 FROM estado_de_cuenta_tmp WHERE usuario='".$_SESSION['usuario_en_sesion']."'";
//echo $sql_reporte;die();
$conexion->exec('USE parquese_pse');

//$sql = "SELECT * FROM organigrama limit 10";
/*************************************************************************/

/*************CONFIGURACION PAR QUE OCURRA LA MAGIA*******/
$PHPJasperXML = new PHPJasperXML();
$PHPJasperXML->arrayParameter=array("query"=>$sql_reporte,
    "parameter1"=>$parameter1,
    "parameter2"=>date("Y-m-d"),
    "parameter3"=>"Contrato:",
    "parameter4"=>"Cuenta:",
    "parameter5"=>"Cliente:",
    "parameter6"=>$parameter6,
    "parameter7"=>$parameter7,
    "parameter8"=>"PAG.",
    "parameter9"=>"Contrato Nro.:");
$PHPJasperXML->load_xml_file("estado_de_cuenta_al_dia.jrxml");
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
ob_end_clean();
$PHPJasperXML->outpage("I");
/*********************************************************/


?>