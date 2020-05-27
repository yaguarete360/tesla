<?php if (!isset($_SESSION)) {session_start();}

include "../librerias/free-pdf/fpdf.php";

class PDF extends FPDF
{
  function Footer()
  {
	$this->SetFont('Arial','',7);
	$alto_footer = 10;
	$this->Cell(0,$alto_footer,"Impreso el ".date('Y-m-d G:i:s')." por ".$_SESSION['usuario_en_sesion'].' - Pagina '.$this->PageNo().'/{nb}',0,0,'C');
  }
}


$prestaciones = "sds,sdc,inh,sdt,exh,exu";
$prestaciones_a = explode(",", $prestaciones);

$prestaciones_nombres = "sepelio,cremacion,inhumacion,traslado,exhumacion,exunilateral";
$prestaciones_nombres_a = explode(",", $prestaciones_nombres);

$prestacion_tipo_seleccion = explode("-", $_POST['prestacion_codigo']);
$prestacion_tipo_posicion = array_search(strtolower($prestacion_tipo_seleccion[1]), $prestaciones_a);
$prestacion_tipo = $prestaciones_nombres_a[$prestacion_tipo_posicion];

include "../vistas/datos/difuntos.php";
include "../funciones/conectar-base-de-datos.php";

$consulta = 
'SELECT *
FROM difuntos
WHERE borrado LIKE "no"
AND LOWER(codigo) LIKE "'.strtolower($_POST['prestacion_codigo']).'"'
;
$query = $conexion->prepare($consulta);
$query->execute();
while($rows = $query->fetch(PDO::FETCH_ASSOC))
{
	$origen_del_dato = $rows['origen_del_dato'];
	$titular = $rows['titular'];
	$fecha = $rows['fecha'];
	$difunto = $rows['difunto'];
	$numero_papeleria = $rows['numero_papeleria'];
	$monto_diferido = $rows['monto_diferido'];
	// $monto_diferido_texto = $rows['monto_diferido_texto'];
	$monto_lista = $rows['monto_lista'];
	$documento_tipo = $rows['titular_documento_tipo'];
	$documento_numero = $rows['titular_documento_numero'];
	$direccion_calle = $rows['direccion_calle'];
	$direccion_numero = $rows['direccion_numero'];
	$direccion_interseccion = $rows['direccion_interseccion'];
	$direccion_barrio = $rows['direccion_barrio'];
	$direccion_ciudad = $rows['direccion_ciudad'];
	$telefono = $rows['telefono'];
	$celular = $rows['celular'];
	$modo = $rows['modo'];
	$cobertura = $rows['cooperativa_o_asociacion'];
	$coopOAso = $rows['cooperativa_o_asociacion'];
	$certificado = $rows['contrato_prepago'];
	$sitio = $rows['sitio'];
	$categoria = $rows['producto'];
	$factura = $rows['factura_numero'];
	$pagare = $rows['pagare_empresa_numero'];
	$observaciones = $rows['observacion'];
	$explotar_fecha = explode("-", $fecha);
	$dia = $explotar_fecha[2];
	$mes = $explotar_fecha[1];
	if($mes == "01") $mes = "Enero";
	if($mes == "02") $mes = "Febrero";
	if($mes == "03") $mes = "Marzo";
	if($mes == "04") $mes = "Abril";
	if($mes == "05") $mes = "Mayo";
	if($mes == "06") $mes = "Junio";
	if($mes == "07") $mes = "Julio";
	if($mes == "08") $mes = "Agosto";
	if($mes == "09") $mes = "Setiembre";
	if($mes == "10") $mes = "Octubre";
	if($mes == "11") $mes = "Noviembre";
	if($mes == "12") $mes = "Diciembre";
	$ano = $explotar_fecha[0];
	foreach($_SESSION['campos'] as $campo_nombre => $campo_atributos)
	{
		$datos[$campo_nombre]['rotulo'] = $campo_nombre;
		$datos[$campo_nombre]['dato'] = $rows[$campo_nombre];
	}
	// if(strtolower($prestacion_tipo) == "cremacion")
	// {
		$titular_nacionalidad = $rows['titular_nacionalidad'];
		$caracter_de = $rows['caracter_de'];
		$documento_tipo_dif = $rows['difunto_documento_tipo'];
		$documento_numero_dif = $rows['difunto_documento_numero'];
		$nacionalidad_dif = $rows['difunto_nacionalidad'];
		$documentacion_caracter = $rows['documentacion_caracter'];
		$causa = $rows['causa'];
		$fecha_defuncion = $rows['defuncion_fecha'];
		$certificado_doctor = $rows['certificado_defuncion_doctor'];
		$certificado_doctor_numero = $rows['certificado_defuncion_doctor_numero'];
		$certificado_numero = $rows['certificado_defuncion_numero'];
		$inicio_fecha = $rows['inicio_fecha'];
		$inicio_hora = $rows['inicio_hora'];
	// }

	// if(strtolower($prestacion_tipo) == "traslado")
	// {
		$cementerio_origen = $rows['cementerio_origen'];
		$cementerio_destino = $rows['cementerio_destino'];
	// }

		$autorizador = $rows['autorizador'];
		$autorizador_documento_tipo = $rows['autorizador_documento_tipo'];
		$autorizador_documento_numero = $rows['autorizador_documento_numero'];
		$autorizador_documentacion_tipo = $rows['autorizador_documentacion_tipo'];
		$autorizador_documentacion_numero = $rows['autorizador_documentacion_numero'];
		$numeroUDS = $rows['uds_numero'];
}

$origen_del_dato = isset($origen_del_dato) ? $origen_del_dato : "sin datos";
$origen_del_dato = !empty($origen_del_dato) ? $origen_del_dato : "sin datos";
$titular = isset($titular) ? $titular : "sin datos";
$titular = !empty($titular) ? $titular : "sin datos";
$titular_nacionalidad = isset($titular_nacionalidad) ? $titular_nacionalidad : "sin datos";
$titular_nacionalidad = !empty($titular_nacionalidad) ? $titular_nacionalidad : "sin datos";
$caracter_de = isset($caracter_de) ? $caracter_de : "sin datos";
$caracter_de = !empty($caracter_de) ? $caracter_de : "sin datos";
$documentacion_caracter = isset($documentacion_caracter) ? $documentacion_caracter : "sin datos";
$documentacion_caracter = !empty($documentacion_caracter) ? $documentacion_caracter : "sin datos";
$fecha = isset($fecha) ? $fecha : "sin datos";
$fecha = !empty($fecha) ? $fecha : "sin datos";
$difunto = isset($difunto) ? $difunto : "sin datos";
$difunto = !empty($difunto) ? $difunto : "sin datos";
$nacionalidad_dif = isset($nacionalidad_dif) ? $nacionalidad_dif : "sin datos";
$nacionalidad_dif = !empty($nacionalidad_dif) ? $nacionalidad_dif : "sin datos";
$fecha_defuncion = isset($fecha_defuncion) ? $fecha_defuncion : "0000-00-00";
$fecha_defuncion = !empty($fecha_defuncion) ? $fecha_defuncion : "0000-00-00";
$causa = isset($causa) ? $causa : "sin datos";
$causa = !empty($causa) ? $causa : "sin datos";
$certificado_doctor = isset($certificado_doctor) ? $certificado_doctor : "sin datos";
$certificado_doctor = !empty($certificado_doctor) ? $certificado_doctor : "sin datos";
$certificado_doctor_numero = isset($certificado_doctor_numero) ? $certificado_doctor_numero : "sin datos";
$certificado_doctor_numero = !empty($certificado_doctor_numero) ? $certificado_doctor_numero : "sin datos";
$certificado_numero = isset($certificado_numero) ? $certificado_numero : "sin datos";
$certificado_numero = !empty($certificado_numero) ? $certificado_numero : "sin datos";
$documento_tipo_dif = isset($documento_tipo_dif) ? $documento_tipo_dif : "sin datos";
$documento_tipo_dif = !empty($documento_tipo_dif) ? $documento_tipo_dif : "sin datos";
$documento_numero_dif = isset($documento_numero_dif) ? $documento_numero_dif : "sin datos";
$documento_numero_dif = !empty($documento_numero_dif) ? $documento_numero_dif : "sin datos";

$autorizador = isset($autorizador) ? $autorizador : "sin datos";
$autorizador = !empty($autorizador) ? $autorizador : "sin datos";
$autorizador_documento_tipo = isset($autorizador_documento_tipo) ? $autorizador_documento_tipo : "sin datos";
$autorizador_documento_tipo = !empty($autorizador_documento_tipo) ? $autorizador_documento_tipo : "sin datos";
$autorizador_documento_numero = isset($autorizador_documento_numero) ? $autorizador_documento_numero : "sin datos";
$autorizador_documento_numero = !empty($autorizador_documento_numero) ? $autorizador_documento_numero : "sin datos";

$autorizador_documentacion_tipo = isset($autorizador_documentacion_tipo) ? $autorizador_documentacion_tipo : "sin datos";
$autorizador_documentacion_tipo = !empty($autorizador_documentacion_tipo) ? $autorizador_documentacion_tipo : "sin datos";
$autorizador_documentacion_numero = isset($autorizador_documentacion_numero) ? $autorizador_documentacion_numero : "sin datos";
$autorizador_documentacion_numero = !empty($autorizador_documentacion_numero) ? $autorizador_documentacion_numero : "sin datos";

$numeroUDS = isset($numeroUDS) ? $numeroUDS : "sin datos";
$numeroUDS = !empty($numeroUDS) ? $numeroUDS : "sin datos";
$numero_papeleria = isset($numero_papeleria) ? $numero_papeleria : "sin datos";
$numero_papeleria = !empty($numero_papeleria) ? $numero_papeleria : "sin datos";
$monto_lista = isset($monto_lista) ? (int)$monto_lista : 0;
$monto_lista = !empty($monto_lista) ? (int)$monto_lista : 0;
$monto_diferido = isset($monto_diferido) ? (int)$monto_diferido : 0;
$monto_diferido = !empty($monto_diferido) ? (int)$monto_diferido : 0;
$monto_diferido_texto = isset($monto_diferido_texto) ? $monto_diferido_texto : 0;
$monto_diferido_texto = !empty($monto_diferido_texto) ? $monto_diferido_texto : 0;
$modo = isset($modo) ? $modo : "sin datos";
$modo = !empty($modo) ? $modo : "sin datos";
$certificado = isset($certificado) ? $certificado : "sin datos";
$certificado = !empty($certificado) ? $certificado : "sin datos";
$sitio = isset($sitio) ? $sitio : "sin datos";
$sitio = !empty($sitio) ? $sitio : "sin datos";
$categoria = isset($categoria) ? $categoria : "sin datos";
$categoria = !empty($categoria) ? $categoria : "sin datos";
$coopOAso = isset($coopOAso) ? $coopOAso : "sin datos";
$coopOAso = !empty($coopOAso) ? $coopOAso : "sin datos";
$inicio_fecha = isset($inicio_fecha) ? $inicio_fecha : "0000-00-00";
$inicio_fecha = !empty($inicio_fecha) ? $inicio_fecha : "0000-00-00";
$documento_tipo = isset($documento_tipo) ? $documento_tipo : "sin datos";
$documento_tipo = !empty($documento_tipo) ? $documento_tipo : "sin datos";
$documento_numero = isset($documento_numero) ? $documento_numero : "sin datos";
$documento_numero = !empty($documento_numero) ? $documento_numero : "sin datos";
$direccion_calle = isset($direccion_calle) ? $direccion_calle : "sin datos";
$direccion_calle = !empty($direccion_calle) ? $direccion_calle : "sin datos";
$direccion_numero = isset($direccion_numero) ? $direccion_numero : "sin datos";
$direccion_numero = !empty($direccion_numero) ? $direccion_numero : "sin datos";
$direccion_interseccion = isset($direccion_interseccion) ? $direccion_interseccion : "sin datos";
$direccion_interseccion = !empty($direccion_interseccion) ? $direccion_interseccion : "sin datos";
$direccion_barrio = isset($direccion_barrio) ? $direccion_barrio : "sin datos";
$direccion_barrio = !empty($direccion_barrio) ? $direccion_barrio : "sin datos";
$direccion_ciudad = isset($direccion_ciudad) ? $direccion_ciudad : "sin datos";
$direccion_ciudad = !empty($direccion_ciudad) ? $direccion_ciudad : "sin datos";
$telefono = isset($telefono) ? $telefono : "sin datos";
$telefono = !empty($telefono) ? $telefono : "sin datos";
$celular = isset($celular) ? $celular : "sin datos";
$celular = !empty($celular) ? $celular : "sin datos";
$factura = isset($factura) ? $factura : "sin datos";
$factura = !empty($factura) ? $factura : "sin datos";
$pagare = isset($pagare) ? $pagare : "sin datos";
$pagare = !empty($pagare) ? $pagare : "sin datos";

if($telefono == "sin datos" or $telefono == "no aplicable" or empty($telefono) or $celular == "sin datos" or $celular == "no aplicable" or empty($celular))
{
	if($telefono != "sin datos" and $telefono != "no aplicable" and !empty($telefono))
	{
		$telefonoFinal = $telefono;
	}
	elseif($celular != "sin datos" and $celular != "no aplicable" and !empty($celular))
	{
		$telefonoFinal = $celular;
	}
	else
	{
		$telefonoFinal = "REVISAR NUMERO DE TELEFONO";
	}
}
else
{
	$telefonoFinal = $telefono."/".$celular;
}

if($fecha == "sin datos")
{
	$dia = "sin datos";
	$mes = "sin datos";
	$ano = "sin datos";
}
if(!isset($datos['difunto']['rotulo']))
{
	foreach($_SESSION['campos'] as $campo_nombre => $campo_atributos)
	{
		$datos[$campo_nombre]['rotulo'] = $campo_nombre;
		$datos[$campo_nombre]['dato'] = "sin datos";
	}	
}
if(strtolower($prestacion_tipo) == "cremacion" or strtolower($prestacion_tipo) == "exhumacion")
{
	$genero = "la";
}
else
{
	$genero = "el";
}

if(isset($modo) and trim(strtolower($modo)) == "particular")
{
	$titulo = 'CONTRATO DE PRESTACION DE SERVICIOS DE '.strtoupper($prestacion_tipo).' Nº. '.$numero_papeleria;
}
else
{
	$titulo = 'SOLICITUD DE PRESTACION DE SERVICIOS DE '.strtoupper($prestacion_tipo).' Nº. '.$numero_papeleria;
}

if(strtolower($monto_lista) == "sin datos")
{
	$monto_lista_palabras = "MODIFICAR MONTO DE LISTA";
	$monto_lista = "MODIFICAR MONTO DE LISTA";
}
else
{
	if(strpos($monto_lista, ".")) $monto_lista = str_replace(".", "", $monto_lista);
	if(strpos($monto_lista, ",")) $monto_lista = str_replace(",", "", $monto_lista);
	$montoAUsar = $monto_lista;
	include '../funciones/poner-montos-en-letras.php';
	$monto_lista_palabras = $montoFinalLetras;
	$monto_lista = number_format($monto_lista,0,",",".");
}

$psmOPsv = explode("-", $certificado);
if(isset($psmOPsv[1]) and strtolower($psmOPsv[1]) == "psv") $tipoDeContrato = "Vitalicio de Prestación de Servicio de ".ucfirst($prestacion_tipo);
if(isset($psmOPsv[1]) and strtolower($psmOPsv[1]) == "psm") $tipoDeContrato = "Privado de Prestación de Servicio de ".ucfirst($prestacion_tipo);

//------------------------------------
if(isset($psmOPsv[1]) and strtolower($psmOPsv[1]) == "psc") $tipoDeContrato = "Privado de Prestación de Servicio de ".ucfirst($prestacion_tipo);//VER
if(isset($psmOPsv[1]) and strtolower($psmOPsv[1]) == "psi") $tipoDeContrato = "Privado de Prestación de Servicio de ".ucfirst($prestacion_tipo);//VER
if(!isset($tipoDeContrato)) $tipoDeContrato = "Privado de Prestación de Servicio de ".ucfirst($prestacion_tipo);//VER

//------------------------------------

$esNombre = explode(".",$_SESSION['alias_en_sesion']);
$coordinador = isset($esNombre[1]) ? ucwords(strtolower($esNombre[0]))." ".ucwords(strtolower($esNombre[1])) : ucwords(strtolower($esNombre[0]));

$coordinador_ced_t = $_SESSION['cedula_en_sesion'];
if(strpos($_SESSION['cedula_en_sesion'], ".")) $coordinador_ced_t = str_replace(".", "", $_SESSION['cedula_en_sesion']);
if(strpos($_SESSION['cedula_en_sesion'], ",")) $coordinador_ced_t = str_replace(",", "", $coordinador_ced_t);
$coordinador_ced = $coordinador_ced_t;


if(ctype_digit($documento_numero))
{
	$documento_numero = number_format($documento_numero,0,",",".");
}
else
{
	$documento_numero_t = $documento_numero;
	if(strpos($documento_numero_t, ".")) $documento_numero_t = str_replace(".", "", $documento_numero_t);
	if(strpos($documento_numero_t, ",")) $documento_numero_t = str_replace(",", "", $documento_numero_t);
	if(ctype_digit($documento_numero_t)) $documento_numero = number_format($documento_numero_t,0,",",".");
}

if(ctype_digit($documento_numero_dif))
{
	$documento_numero_dif = number_format($documento_numero_dif,0,",",".");
}
else
{
	$documento_numero_t = $documento_numero_dif;
	if(strpos($documento_numero_t, ".")) $documento_numero_t = str_replace(".", "", $documento_numero_t);
	if(strpos($documento_numero_t, ",")) $documento_numero_t = str_replace(",", "", $documento_numero_t);
	if(ctype_digit($documento_numero_t)) $documento_numero_dif = number_format($documento_numero_t,0,",",".");
}

//if(!empty($autorizador) and $autorizador != "sin datos")
if(isset($_POST['firmante']) and $_POST['firmante'] == "autorizador")
{
	$titular_firma = ucwords($autorizador);
	$documento_tipo_firma = $autorizador_documento_tipo;
	if(ctype_digit($autorizador_documento_numero))
	{
		$documento_numero_firma = number_format($autorizador_documento_numero,0,",",".");
	}
	else
	{
		$documento_numero_firma = $autorizador_documento_numero;
	}
}
else
{
	$titular_firma = $titular;
	$documento_tipo_firma = $documento_tipo;
	$documento_numero_firma = $documento_numero;
}
if(strpos($titular, "ñ")) $titular = str_replace("ñ", "Ñ", $titular);
if(strpos($difunto, "ñ")) $difunto = str_replace("ñ", "Ñ", $difunto);

if($direccion_interseccion == "no aplicable" or $direccion_interseccion == "sin datos")
{
	$direccion_proc = " del barrio ".strtoupper($direccion_barrio)." de la ciudad de ".strtoupper($direccion_ciudad);
}
else
{
	$direccion_proc = " casi ".strtoupper($direccion_interseccion)." del barrio ".strtoupper($direccion_barrio)." de la ciudad de ".strtoupper($direccion_ciudad);
}

$dia_nombre = "";
$dia_inicio = date('w',strtotime($inicio_fecha));
switch ($dia_inicio)
{
	case '0':
		$dia_nombre = "Domingo";
	break;
	case '1':
		$dia_nombre = "Lunes";
	break;
	case '2':
		$dia_nombre = "Martes";
	break;
	case '3':
		$dia_nombre = "Miercoles";
	break;
	case '4':
		$dia_nombre = "Jueves";
	break;
	case '5':
		$dia_nombre = "Viernes";
	break;
	case '6':
		$dia_nombre = "Sabado";
	break;
	default:
		$dia_nombre = "ERROR";
	break;
}

if($factura == "sin datos")
{
	$factura_cre = "        ";
}
else
{
	$factura_cre = " ".$factura;
}

// $agregadoEntreComas = "";
// if($prestacion_tipo == "Traslado" and $categoria != "SDT-Sin Tramites y Sin Transporte") $agregadoEntreComas = ", de ".$cementerio_origen.", a ".$cementerio_destino.",";

if(strtolower($prestacion_tipo) == "cremacion")
{
	if(isset($psmOPsv[1]) and strtolower($psmOPsv[1]) == "psc")
	{
		$FinalPorTipoDeCremacion = ", y solicito la otorgación del servicio de cremación en virtud del Contrato P.S.C. Nº. ".$psmOPsv[2].", según otorgación (O.T.D. Nº.         ). Él/la autorizante manifiesta bajo Fé de Juramento que esta suficientemente acreditado/a a solicitar el presente Servicio de Cremación (S.D.C.) y asume toda responsabilidad ante PARQUE SERENIDAD S.R.L. y/o terceros, por cualquier consecuencia derivada del Servicio.";
	}
	else
	{
		$FinalPorTipoDeCremacion = ". El costo del S.D.C. es de Guaraníes ".strtoupper($monto_lista_palabras)." (Gs. ".$monto_lista.") pagado según Factura Contado Nº. ".$factura_cre.". Él/la autorizante manifiesta bajo Fé de Juramento que esta suficientemente acreditado/a a solicitar el presente Servicio de Cremación (S.D.C.) y asume toda responsabilidad ante PARQUE SERENIDAD S.R.L. y/o terceros, por cualquier consecuencia derivada del Servicio.";
	}
}

$codigoEx = explode("-",$_POST['prestacion_codigo']);
$siglasPuntos = strtoupper($codigoEx[1][0].".".$codigoEx[1][1].".".$codigoEx[1][2].".");

$sitioEx = explode("-", $sitio);

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->SetMargins(17, 11, 10.5);
$pdf->SetAutoPageBreak('auto', 30);
$pdf->AddPage();
// $pdf->Image($url.'pse-red/iconos/logo-pdf.png',77,9,60,40,'PNG');
$pdf->Ln();
$pdf->SetFont('Arial','B',20);//Quitar cuando termine periodo de prueba
$pdf->Cell(0,30,"",0,0,'C');//linea original: $pdf->Cell(0,40,"");
$pdf->Ln();
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,5, iconv("UTF-8", "ISO-8859-1", trim($titulo)),0,0,'C');
$pdf->Ln();

$pdf->SetFont('Arial','B',10);
if($modo == "prepago")
{
	$pdf->Cell(0,8, iconv("UTF-8", "ISO-8859-1", trim("Código: ".strtoupper($_POST['prestacion_codigo'])." Certificado: ".$certificado." Categoria: ".$categoria)),0,0,'C');
	$pdf->Ln();
	if(strtolower($psmOPsv[1]) == "psv" and strtolower($prestacion_tipo) == "sepelio")
	{
		
		$pdf->Cell(0,8,iconv("UTF-8", "ISO-8859-1", trim(str_replace("\xc2\xa0",'',$coopOAso))),0,0,'C');
		$pdf->Ln();
	}
}
else
{
	$pdf->Cell(0,8, iconv("UTF-8", "ISO-8859-1", trim("Código: ".strtoupper($_POST['prestacion_codigo'])." Categoria: ".$categoria)),0,0,'C');
	$pdf->Ln();
}

//--------------------------------------------------------------------------------------------------------------------------------------

if(strtolower($prestacion_tipo) == "cremacion")
{
	if(isset($modo) and (trim(strtolower($modo)) == "particular" or trim($modo) == "no aplicable"))
	{
	}
	else
	{
		$tipoDeCoberL = "en virtud del";
		$tipoDeCoberP = "Certificado ".trim($certificado)." categoria ".trim($categoria);
	}

	if($autorizador != "sin datos")
	{
		$autorizador_documento_numero = (ctype_digit($autorizador_documento_numero)) ? number_format($autorizador_documento_numero) : $autorizador_documento_numero;
		$autorizador_documentacion_numero = (ctype_digit($autorizador_documentacion_numero)) ? number_format($autorizador_documentacion_numero) : $autorizador_documentacion_numero;
		
		$caracter_de_tiene_autorizador_texto = ' de el/la señor/a '.ucwords($autorizador).' con '.ucwords($autorizador_documento_tipo).' Nº. '.$autorizador_documento_numero.' respaldado por el '.ucwords($autorizador_documentacion_tipo).' Nº. '.$autorizador_documentacion_numero;
	}
	else
	{
		$caracter_de_tiene_autorizador_texto = '';
	}

	$pdf->SetFont('Arial','I',10);
	$pdf->Cell(0,10, iconv("UTF-8", "ISO-8859-1", "Asunción, ".$dia." de ".$mes." de ".$ano),0,0,'R');
	$pdf->Ln();
	// $caracter_de_tiene_autorizador_texto
	$pdf->MultiCell(0,7, iconv("UTF-8", "ISO-8859-1", str_replace("ñ", "Ñ", "Por medio del presente instrumento yo ".strtoupper($titular).", con ".strtoupper($documento_tipo)." Nº. ".$documento_numero." de nacionalidad ".strtoupper($titular_nacionalidad).", con domicilio en ".strtoupper($direccion_calle)." Nº. ".$direccion_numero.$direccion_proc." con telefono Nº. ".$telefonoFinal." en mi caracter de ".strtoupper($caracter_de)." de quien en vida fuera ".strtoupper($difunto).", con ".strtoupper($documento_tipo_dif)." Nº. ".$documento_numero_dif." de nacionalidad ".strtoupper($nacionalidad_dif).", según lo acredito con la siguiente documentación ".strtoupper($documentacion_caracter).", manifiesto bajo Fé de juramento que autorizo a PARQUE SERENIDAD S.R.L. a realizar la cremación de los restos de quien en vida fuera ".strtoupper($difunto)." cuya defunción se produjo por (causa) ".strtoupper($causa)." en fecha ".$fecha_defuncion." conforme lo acredito con el correspondiente Certificado de Defunción del Ministerio de Salud Pública firmado por el Dr/a. ".strtoupper($certificado_doctor)." Reg. Prof. Nº. ".$certificado_doctor_numero." y/o Certificado de Acta de Defunción del Registro Civil Nº. ".$certificado_numero.", cuyo/s original/es y/o copia/s debidamente autenticada/s queda/n adjuntado/s a este documento".$FinalPorTipoDeCremacion)));
	// $pdf->MultiCell(0,7, iconv("UTF-8", "ISO-8859-1", str_replace("ñ", "Ñ", "Por medio del presente instrumento yo ".strtoupper($titular).", con ".strtoupper($documento_tipo)." Nº. ".$documento_numero." de nacionalidad ".strtoupper($titular_nacionalidad).", con domicilio en ".strtoupper($direccion_calle)." Nº. ".$direccion_numero.$direccion_proc." con telefono Nº. ".$telefonoFinal." en mi caracter de ".strtoupper($caracter_de).$caracter_de_tiene_autorizador_texto." de quien en vida fuera ".strtoupper($difunto).", con ".strtoupper($documento_tipo_dif)." Nº. ".$documento_numero_dif." de nacionalidad ".strtoupper($nacionalidad_dif).", según lo acredito con la siguiente documentación ".strtoupper($documentacion_caracter).", manifiesto bajo Fé de juramento que autorizo a PARQUE SERENIDAD S.R.L. a realizar la cremación de los restos de quien en vida fuera ".strtoupper($difunto)." cuya defunción se produjo por (causa) ".strtoupper($causa)." en fecha ".$fecha_defuncion." conforme lo acredito con el correspondiente Certificado de Defunción del Ministerio de Salud Pública firmado por el Dr/a. ".strtoupper($certificado_doctor)." Reg. Prof. Nº. ".$certificado_doctor_numero." y/o Certificado de Acta de Defunción del Registro Civil Nº. ".$certificado_numero.", cuyo/s original/es y/o copia/s debidamente autenticada/s queda/n adjuntado/s a este documento".$FinalPorTipoDeCremacion)));
	$pdf->MultiCell(0,6, iconv("UTF-8", "ISO-8859-1", "Nota: La presente autorización tambien tiene caracter de Contrato de Mandato de acuerdo a las disposiciones del Código Civil Paraguayo."));
	if($observaciones!="sin datos" and $observaciones!="no aplicable")
	{
	    $observaciones_cremaciones = $observaciones;
	}else
	{
	    $observaciones_cremaciones= "";
	}
	$pdf->MultiCell(0,6, iconv("UTF-8", "ISO-8859-1", "Observaciones: La cremación se realiza el dia ".$dia_nombre." ".$inicio_fecha.". ".ucfirst($observaciones_cremaciones)."." ));
}
elseif(strtolower($prestacion_tipo) == "traslado")
{
	$pdf->SetFont('Arial','I',10);
	$pdf->Cell(0,10, iconv("UTF-8", "ISO-8859-1", "Asunción, ".$dia." de ".$mes." de ".$ano),0,0,'R');
	$pdf->Ln();
	if(strpos(strtolower($cementerio_destino), "parque serenidad"))
	{
		$entraOSaleTraslado = "ingreso";
	}
	else
	{
		$entraOSaleTraslado = "traslado";
	}
	
	if(strpos($cementerio_origen, ","))
	{
		$cementerio_origen_partes = explode(",", $cementerio_origen);
		$cementerio_origen_final = $cementerio_origen_partes[1]." de ".$cementerio_origen_partes[0];
	}
	else
	{
		$cementerio_origen_final = $cementerio_origen;
	}

	if(strpos($cementerio_destino, ","))
	{
		$cementerio_destino_partes = explode(",", $cementerio_destino);
		$cementerio_destino_final = $cementerio_destino_partes[1]." de ".$cementerio_destino_partes[0];
	}
	else
	{
		$cementerio_destino_final = $cementerio_destino;
	}

	$pdf->MultiCell(0,7, iconv("UTF-8", "ISO-8859-1", str_replace("ñ", "Ñ", "Parque Serenidad a solicitud de ".strtoupper($titular).", con ".strtoupper($documento_tipo)." Nº. ".$documento_numero." de nacionalidad ".strtoupper($titular_nacionalidad).", autoriza el ".$entraOSaleTraslado." de quien en vida fuera ".strtoupper($difunto).", de ".$cementerio_origen_final.", a ".$cementerio_destino_final.".")));

	if(strtolower($categoria) == "sdt-sin tramites y sin transporte")
	{
		$pdf->MultiCell(0,7, iconv("UTF-8", "ISO-8859-1", str_replace("ñ", "Ñ", "Todos los trámites administrativos y gestiones relacionadas al traslado corren por cuenta y cargo del solicitante.")));
	}
	else
	{
		$pdf->Cell(148,6, iconv("UTF-8", "ISO-8859-1", "Acepta hacerse cargo del precio de dicho servicio, el cual ascienda a la suma de "),0,0,'L');
		$pdf->Ln();
		$pdf->SetFont('Arial','B', 9);
		$pdf->Cell(0,9, iconv("UTF-8", "ISO-8859-1", "Guaraníes ".strtoupper(str_replace("  ", " ", $monto_lista_palabras))),0,0,'C');
		$pdf->Ln();	
		$pdf->Cell(0,5, iconv("UTF-8", "ISO-8859-1", "(Gs. ".$monto_lista.")"),0,0,'C');
		$pdf->Ln();	
	}
	$pdf->SetFont('Arial','I',10);
	$pdf->MultiCell(0,6, iconv("UTF-8", "ISO-8859-1", "Nota: La presente autorización tambien tiene caracter de Contrato de Mandato de acuerdo a las disposiciones del Código Civil Paraguayo."));
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(60,2, "",0,0,'L');
	$pdf->Ln();
	if(empty($observaciones) or $observaciones == "sin datos" or $observaciones == "no aplicable")
	{
		$pdf->Cell(60,6, "OBSERVACIONES: ___________________________________________________________________________________________");
		$pdf->Ln();
		$pdf->Cell(60,6, "____________________________________________________________________________________________________________",0,0,'L');
		$pdf->Ln();
		$pdf->Cell(60,6, "____________________________________________________________________________________________________________",0,0,'L');
	}
	else
	{
		$pdf->MultiCell(0,6, "OBSERVACIONES: ".$observaciones);
	}
	$pdf->SetFont('Arial','I',10);
}
else
{
	// $pdf->Ln();
	// $pdf->Cell(0,10,"");
	// $pdf->Ln();
	$pdf->SetFont('Arial','I',10);
	$pdf->MultiCell(0,6, iconv("UTF-8", "ISO-8859-1", "En Asunción, a los ".$dia." dias del mes ".$mes." de ".$ano.", por medio de la presente, el/la Señor/a"));
	// $pdf->Ln();


	if($autorizador != "sin datos")
	{
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(0,10, iconv("UTF-8", "ISO-8859-1", strtoupper(trim($autorizador))),0,0,'C');
		$pdf->Ln();		
		$pdf->SetFont('Arial','I',10);
		$pdf->MultiCell(0,6, iconv("UTF-8", "ISO-8859-1", "con ".str_replace("Cedula", "Cédula",$autorizador_documento_tipo)." Nº. ".$autorizador_documento_numero." y domicilio en la calle ".strtoupper($direccion_calle)." Nº. ".$direccion_numero.$direccion_proc." con telefono Nº. ".$telefonoFinal." autoriza a PARQUE SERENIDAD S.R.L. a realizar ".$genero." ".$prestacion_tipo." de quien en vida fuera:"));
	}
	else
	{
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(0,10, iconv("UTF-8", "ISO-8859-1", strtoupper(trim($titular))),0,0,'C');
		$pdf->Ln();		
		$pdf->SetFont('Arial','I',10);
		$pdf->MultiCell(0,6, iconv("UTF-8", "ISO-8859-1", "con ".str_replace("Cedula", "Cédula",$documento_tipo)." Nº. ".$documento_numero." y domicilio en la calle ".strtoupper($direccion_calle)." Nº. ".$direccion_numero.$direccion_proc." con telefono Nº. ".$telefonoFinal." autoriza a PARQUE SERENIDAD S.R.L. a realizar ".$genero." ".$prestacion_tipo." de quien en vida fuera:"));
	}

	// $pdf->MultiCell(0,6, iconv("UTF-8", "ISO-8859-1", "con ".str_replace("Cedula", "Cédula",$documento_tipo)." Nº. ".$documento_numero." y domicilio en la calle ".strtoupper($direccion_calle)." Nº. ".$direccion_numero.$direccion_proc." con telefono Nº. ".$telefonoFinal." autoriza a PARQUE SERENIDAD S.R.L. a realizar ".$genero." ".$prestacion_tipo.$agregadoEntreComas." de quien en vida fuera:"));
	// $pdf->Ln();
	// $pdf->Ln();
	$pdf->SetFont('Arial','B',12);
	$difunto = iconv("UTF-8", "ISO-8859-1", "+ ".strtoupper(trim($difunto)));
	$pdf->Cell(0,10, $difunto,0,0,'C');
	$pdf->Cell(0,10, "",0,0,'C');
	// $pdf->Ln();
	$pdf->Ln();
	$pdf->SetFont('Arial','I',10);
	if(isset($modo) and (trim(strtolower($modo)) == "particular" or trim($modo) == "no aplicable"))
	{
		if(strtolower($prestacion_tipo) == "sepelio")
		{
			$pdf->Cell(148,6, iconv("UTF-8", "ISO-8859-1", "Así también, acepta hacerse cargo del precio de dicho servicio, el cual ascienda a la suma de "),0,0,'L');
			$pdf->Ln();
			$pdf->SetFont('Arial','B',9);
			// $pdf->Cell(0,9, iconv("UTF-8", "ISO-8859-1", "Guaraníes ".strtoupper(str_replace("  ", " ", $monto_lista_palabras))." más I.V.A."),0,0,'C');
			$pdf->Cell(0,9, iconv("UTF-8", "ISO-8859-1", "Guaraníes ".strtoupper(str_replace("  ", " ", $monto_lista_palabras))." I.V.A. incluído"),0,0,'C');
			$pdf->Ln();	
			// $pdf->Cell(0,5, iconv("UTF-8", "ISO-8859-1", "(Gs. ".$monto_lista.") + I.V.A."),0,0,'C');
			$pdf->Cell(0,5, iconv("UTF-8", "ISO-8859-1", "(Gs. ".$monto_lista.") I.V.A. incluído"),0,0,'C');
		}
		else
		{
			$pdf->Cell(148,6, iconv("UTF-8", "ISO-8859-1", "Así también, acepta hacerse cargo del precio de dicho servicio, el cual ascienda a la suma de "),0,0,'L');
			$pdf->Ln();
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(0,9, iconv("UTF-8", "ISO-8859-1", "Guaraníes ".strtoupper(str_replace("  ", " ", $monto_lista_palabras))),0,0,'C');
			$pdf->Ln();	
			$pdf->Cell(0,5, iconv("UTF-8", "ISO-8859-1", "(Gs. ".$monto_lista.")"),0,0,'C');
		}
	}
	else
	{
		$tipoDeCoberL = "";
		if(trim(strtolower($coopOAso)) == "particular" or trim(strtolower($coopOAso)) == "sin datos" or trim(strtolower($coopOAso)) == "sin asociacion")
		{
			$tipoDeCoberL = "en virtud del";
			$tipoDeCoberP = "certificado ".trim($certificado)." categoria ".trim($categoria)." cuyo precio de lista es de Guaraníes";
			$tipoDeCoberP2 = strtoupper(str_replace("  ", " ", $monto_lista_palabras))." (Gs. ".$monto_lista.").";
		}
		else
		{
			$tipoDeCoberL = "a traves de";
			$tipoDeCoberP = trim($coopOAso)." en virtud del certificado ".trim($certificado)." categoria ".trim($categoria);
			$tipoDeCoberP2 = "cuyo precio de lista es de Guaraníes ".strtoupper(str_replace("  ", " ", $monto_lista_palabras))." (Gs. ".$monto_lista.").";
		}
		$pdf->MultiCell(0,6, iconv("UTF-8", "ISO-8859-1", "Así tambien reconoce que la categoría y el valor de la prestación corresponden a lo establecido en el correspondiente Contrato ".$tipoDeContrato." del cual solicita la COBERTURA ".$tipoDeCoberL." ".$tipoDeCoberP." ".$tipoDeCoberP2),0);

		$pdf->SetFont('Arial','B',12);
		// $pdf->Cell(0,10, iconv("UTF-8", "ISO-8859-1", ""),0,0,'C');
	}

	$pdf->SetFont('Arial','I',10);
	$pdf->Ln();
	if(!empty($autorizador) and $autorizador != "sin datos")
	{
		// if(strtolower($prestacion_tipo) == "inhumacion")
		if(strtolower($prestacion_tipo) == "inhumacion" or strtolower($prestacion_tipo) == "exhumacion")
		{
			if(!empty($numeroUDS) and $numeroUDS != "sin datos")
			{
				$tieneUDS = "U.D.S. Nº ".$numeroUDS;
			}
			else
			{
				$tieneUDS = "U.D.S. Nº.:_____________";
			}
		}
		else
		{
			$tieneUDS = $certificado;
		}
		$pdf->MultiCell(0,6, iconv("UTF-8", "ISO-8859-1", "El/La AUTORIZANTE expresa Fé de Juramento que esta suficientemente facultado/a por el/la Señor/a ".$titular.", TITULAR del ".$tieneUDS.", a solicitar el presente servicio de cuyos datos adjuntos proveidos se hace responsable y declara también conocer y aceptar que la presente tiene calidad de CONTRATO de acuerdo a las disposiciones del Código Civil Paraguayo al cual se compromete como tal."));
	}
	else
	{
		$pdf->MultiCell(0,6, iconv("UTF-8", "ISO-8859-1", "El/La TITULAR expresa Fé de Juramento que esta suficientemente acreditado/a a solicitar el presente servicio de cuyos datos adjuntos proveidos se hace responsable y declara también conocer y aceptar que la presente tiene calidad de CONTRATO de acuerdo a las disposiciones del Código Civil Paraguayo al cual se compromete como tal."));
	}
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(60,2, "",0,0,'L');
	$pdf->Ln();
	if(empty($observaciones) or strtolower($observaciones) == "sin datos" or strtolower($observaciones) == "no aplicable")
	{
		$pdf->Cell(60,6, "OBSERVACIONES: ___________________________________________________________________________________________");
		$pdf->Ln();
		$pdf->Cell(60,6, "____________________________________________________________________________________________________________",0,0,'L');
		$pdf->Ln();
		$pdf->Cell(60,6, "____________________________________________________________________________________________________________",0,0,'L');
	}
	else
	{
		$pdf->MultiCell(0,6, "OBSERVACIONES: ".$observaciones);
	}
}
	$pdf->Cell(0,10, "",0,0,'C');
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(90,7, "________________________________",0,0,'L');
	$pdf->Cell(90,7, "________________________________",0,0,'L');
	$pdf->Ln();		
	$pdf->SetFont('Arial','I',10);
	$pdf->Cell(90,5, iconv("UTF-8", "ISO-8859-1", ucwords($coordinador)),0,0,'L');
	$pdf->Cell(90,5, iconv("UTF-8", "ISO-8859-1", ucwords($titular_firma)),0,0,'L');
	$pdf->Ln();
	$pdf->Cell(90,5, "Coordinador",0,0,'L');
	
	
	// if(!empty($autorizador) and $autorizador != "sin datos")
	if(isset($_POST['firmante']) and strtolower($_POST['firmante']) == "autorizador")
	{
		$pdf->Cell(90,5, "Autorizante",0,0,'L');
	}
	else
	{
		$pdf->Cell(90,5, "Titular",0,0,'L');
	}
	$pdf->Ln();
	$pdf->SetFont('Arial','I',8);
	//$pdf->Cell(90,5, "",0,0,'L');
	(ctype_digit($coordinador_ced)) ? $cedula_coordinador = number_format($coordinador_ced,0,",",".") : $cedula_coordinador = $coordinador_ced;
	$pdf->Cell(90,5, iconv("UTF-8", "ISO-8859-1", trim("Cédula de Identidad Nº: ".$coordinador_ced)),0,0,'L');
	// $pdf->Cell(90,5, iconv("UTF-8", "ISO-8859-1", trim(str_replace("Cedula", "Cédula", $documento_tipo_firma)." Nº: ".number_format($documento_numero_firma,0,",","."))),0,0,'L');
	$pdf->Cell(90,5, iconv("UTF-8", "ISO-8859-1", trim(str_replace("Cedula", "Cédula", $documento_tipo_firma)." Nº: ".$documento_numero_firma)),0,0,'L');
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(170,5, "USO INTERNO",'T',0,'L');
	$pdf->SetFont('Arial','',9);
	$pdf->Ln();

	if(strtolower($prestacion_tipo) == "cremacion")
	{
		$usarSDC = $numero_papeleria;
	}
	else
	{
		$usarSDC = "_____________";
	}

	//if($prestacion_tipo == "Inhumacion")
	if(strtolower($prestacion_tipo) == "inhumacion" or strtolower($prestacion_tipo) == "exhumacion")
	{
		$tieneOTDSi = "U.D.S. Nº.:_____________";
	}
	else
	{
		$tieneOTDSi = "O.T.D. Nº.:_____________";
	}

	if(strtolower($prestacion_tipo) == "traslado")
	{
		$pdf->MultiCell(0,6, iconv("UTF-8", "ISO-8859-1", $siglasPuntos." Nº.:".$usarSDC."                   U.D.S. Nº.:_____________"));
		$pdf->MultiCell(0,6, iconv("UTF-8", "ISO-8859-1", "C.T.A. Nº.:_____________                   S.D.C. Nº.:_____________"));
	}
	else
	{
		$pdf->MultiCell(0,6, iconv("UTF-8", "ISO-8859-1", $siglasPuntos." Nº.:".$usarSDC."    ".$tieneOTDSi."    C.T.A. Nº.:_____________"));
	}
	
	if(strtolower($prestacion_tipo) == "inhumacion" or strtolower($prestacion_tipo) == "exhumacion")
	{
		$pdf->SetFont('Arial', 'B', 12);
		$elem_sitios = count($sitioEx);
		$pdf->MultiCell(0,6, iconv("UTF-8", "ISO-8859-1", "Sitio: ".$sitioEx[$elem_sitios-1]."    Sendero: ".$sitioEx[$elem_sitios-2]."    Area: ".$sitioEx[$elem_sitios-3]."    Linea: ".$sitioEx[$elem_sitios-4]."    Emprendimiento: ".$sitioEx[$elem_sitios-5]));
		// $pdf->Ln();
		$pdf->MultiCell(0,6, iconv("UTF-8", "ISO-8859-1", "Fecha: ".$inicio_fecha."    Hora: ".$inicio_hora));
		$pdf->Ln();
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(1,5, " ",0,0,'L');	
		$pdf->Cell(44,5, "_________________________",0,0,'L');
		$pdf->Cell(44,5, "_________________________",0,0,'L');
		$pdf->Cell(44,5, "_________________________",0,0,'L');
		$pdf->Cell(44,5, "_________________________",0,0,'L');
		$pdf->Ln();		
		$pdf->Cell(1,5, " ",0,0,'L');	
		$pdf->Cell(44,5, iconv("UTF-8", "ISO-8859-1", "Aclaración:________________"),0,0,'L');
		$pdf->Cell(44,5, iconv("UTF-8", "ISO-8859-1", "Aclaración:________________"),0,0,'L');
		$pdf->Cell(44,5, iconv("UTF-8", "ISO-8859-1", "Aclaración:________________"),0,0,'L');
		$pdf->Cell(44,5, iconv("UTF-8", "ISO-8859-1", "Aclaración:________________"),0,0,'L');
		// $pdf->Cell(44,5, "Fernando Ibarra",0,0,'L');
		$pdf->Ln();		
		$pdf->Cell(1,5, " ",0,0,'L');		
		$pdf->Cell(44,5, "",0,0,'L');
		// $pdf->Cell(44,5, "Servicios",0,0,'L');
		// $pdf->Cell(44,4, "Gerencia Operativa",0,0,'L');
		// $pdf->Cell(44,4, iconv("UTF-8", "ISO-8859-1", "Administración"),0,0,'L');
		// $pdf->Cell(44,4, "Gerente General",0,0,'L');
		$pdf->Ln();
	}


$pdf->SetMargins(17, 1, 10.5);
$pdf->AddPage();
//$pdf->Image($url.'pse-red/iconos/logo-pdf.png',87,5,30,20,'PNG');
//$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial','B',15);//Quitar cuando termine periodo de prueba
$pdf->Cell(0,18,"",0,0,'C');//linea original: $pdf->Cell(0,19,"");
$pdf->Ln();
$ind = 0;
foreach($_SESSION['campos'] as $campo_nombre => $campo_atributos)
{
	$otorgado = explode("_", $campo_nombre);
	$campos_que_no_entran_s = "busqueda_funcionario,busqueda_vehiculo,busqueda_tiempo,laboratorio_funcionario,laboratorio_tiempo,traslado_funcionario,traslado_vehiculo,traslado_tiempo,cantina_funcionario_t1,cantina_funcionario_t2,limpieza_funcionario_t1,limpieza_funcionario_t2,tramite_funcionario,tramite_vehiculo,tramite_tiempo,responso_cura,responso_hora,soldadura_funcionario,soldadura_hora,cortejo_funcionario_v1,cortejo_vehiculo_v1,cortejo_funcionario_v2,cortejo_vehiculo_v2,cortejo_funcionario_v3,cortejo_vehiculo_v3,cortejo_funcionario_v4,cortejo_vehiculo_v4,comentario,oculto,concluido,alta_fecha,baja_fecha,baja_motivo,creado,modificado,borrado,ultimo_usuario";
	$campos_que_no_entran_a = explode(",", $campos_que_no_entran_s);

	// if($campo_nombre != "creado" and $campo_nombre != "modificado" and $campo_nombre != "borrado" and $otorgado[0] != "otorgacion")
	if(!in_array($campo_nombre, $campos_que_no_entran_a) and $otorgado[0] != "otorgacion")
	{
		if(strtolower(trim($datos[$campo_nombre]['dato'])) != "no aplicable" and strtolower(trim($datos[$campo_nombre]['dato'])) != "n/a")
		{
			if($ind %2)
			{
				if(strlen(trim($datos[$campo_nombre]['dato'])) > 31)
				{
					$pdf->Ln();
				}
			}
			
			if(strpos($datos[$campo_nombre]['rotulo'], "documento") !== FALSE) $datos[$campo_nombre]['rotulo'] = str_ireplace("documento", "doc.", $datos[$campo_nombre]['rotulo']);
			if(strpos($datos[$campo_nombre]['rotulo'], "certificado") !== FALSE) $datos[$campo_nombre]['rotulo'] = str_ireplace("certificado", "cert.", $datos[$campo_nombre]['rotulo']);
			if(strpos($datos[$campo_nombre]['rotulo'], "doctor") !== FALSE) $datos[$campo_nombre]['rotulo'] = str_ireplace("doctor", "dr.", $datos[$campo_nombre]['rotulo']);
			if(strpos($datos[$campo_nombre]['rotulo'], "numero") !== FALSE) $datos[$campo_nombre]['rotulo'] = str_ireplace("numero", "Nº. ", $datos[$campo_nombre]['rotulo']);
			if(strpos($datos[$campo_nombre]['rotulo'], "ano") !== FALSE) $datos[$campo_nombre]['rotulo'] = str_ireplace("ano", "año", $datos[$campo_nombre]['rotulo']);
			if(strpos($datos[$campo_nombre]['rotulo'], "cooperativa") !== FALSE) $datos[$campo_nombre]['rotulo'] = str_ireplace("cooperativa", "coop.", $datos[$campo_nombre]['rotulo']);
			if(strpos($datos[$campo_nombre]['rotulo'], "  ") !== FALSE) $datos[$campo_nombre]['rotulo'] = str_ireplace("  ", " ", $datos[$campo_nombre]['rotulo']);
			if(strpos($datos[$campo_nombre]['dato'], "  ") !== FALSE) $datos[$campo_nombre]['dato'] = str_ireplace("  ", " ", $datos[$campo_nombre]['dato']);
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(38,4.3, iconv("UTF-8", "ISO-8859-1", trim(ucwords(str_replace("_"," ",$datos[$campo_nombre]['rotulo'])))),0,0,'L');
			$pdf->Cell(3,4.3, ":",0,0,'L');
			$pdf->SetFont('Arial','',9);
			if(trim($datos[$campo_nombre]['rotulo']) == "observacion")
			{
				$pdf->MultiCell(0,4.3, iconv("UTF-8", "ISO-8859-1", trim($datos[$campo_nombre]['dato'])));
			}
			else
			{
				$pdf->Cell(49,4.3, iconv("UTF-8", "ISO-8859-1", trim($datos[$campo_nombre]['dato'])),0,0,'L');
			}

			if($ind %2)
			{
				$pdf->Ln();
			}
			else
			{
				if(strlen(trim($datos[$campo_nombre]['dato'])) > 31)
				{
					$pdf->Ln();
					$ind--;
				}
			}

			$ind++;
		}
	}
}
$pdf->Ln();
// $pdf->Ln();
$pdf->SetFont('Arial','',8);		
// $pdf->Cell(1,5, " ",0,0,'L');	
// $pdf->Cell(60,5, "_______________________________",0,0,'L');

$pdf->Cell(0,5, "_______________________________",0,0,'L');

$pdf->Ln();		
// $pdf->Cell(1,5, " ",0,0,'L');
// $pdf->Cell(60,5, iconv("UTF-8", "ISO-8859-1", "Aclaración:______________________"),0,0,'L');
$pdf->Cell(0,5, iconv("UTF-8", "ISO-8859-1", "Aclaración:______________________"),0,0,'L');

$pdf->Ln();
// $pdf->Cell(1,5, " ",0,0,'L');		
// $pdf->Cell(60,5, "Servicios",0,0,'L');
$pdf->Cell(0,5, "Servicios",0,0,'L');

$pdf->Ln();
$pdf->Ln();
if(isset($modo) and trim(strtolower($modo)) == "prepago")
{
	
	$titulo = 'CONTRATO DE OTORGACION DE DERECHOS DE '.strtoupper($prestacion_tipo).' Nº. '.$numero_papeleria;
	$pdf->SetMargins(17, 11, 10.5);
	$pdf->AddPage();
	// $pdf->Image($url.'pse-red/iconos/logo-pdf.png',77,9,60,40,'PNG');
	$pdf->Ln();
	$pdf->SetFont('Arial','B',20);//Quitar cuando termine periodo de prueba
	$pdf->Cell(0,30,"",0,0,'C');//linea original: $pdf->Cell(0,40,"");
	$pdf->Ln();
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(0,5, iconv("UTF-8", "ISO-8859-1", trim($titulo)),0,0,'C');
	$pdf->Ln();
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(0,8, iconv("UTF-8", "ISO-8859-1", trim("Código: ".strtoupper($_POST['prestacion_codigo'])." Certificado: ".$certificado." Categoria: ".$categoria)),0,0,'C');
	// $pdf->Cell(0,8, iconv("UTF-8", "ISO-8859-1", trim("Código: ".$_POST['prestacion_codigo']." Certificado: ".$certificado." Categoria: ".$categoria)),0,0,'C');
	$pdf->Ln();
	if(strtolower($psmOPsv[1]) == "psv" and strtolower($prestacion_tipo) == "sepelio")
	{
		if(strtolower($coopOAso) != "particular")
		{
			$pdf->Cell(0,3,iconv("UTF-8", "ISO-8859-1", trim(str_replace("\xc2\xa0",'',$coopOAso))),0,0,'C');
			$pdf->Ln();
		}
	}
	$pdf->Cell(0,1,"");
	$pdf->Ln();
	$pdf->SetFont('Arial','I',10);
	$pdf->Cell(0,6, iconv("UTF-8", "ISO-8859-1", "En Asunción, a los ".$dia." del mes  ".$mes." de ".$ano.", por medio de la presente, el/la Señor/a"),0,0,'L');
	$pdf->Ln();
	$pdf->SetFont('Arial','B',12);		
	$pdf->Cell(0,10, iconv("UTF-8", "ISO-8859-1", strtoupper(trim($titular))),0,0,'C');			
	$pdf->Ln();		
	$pdf->SetFont('Arial','I',10);

	$pdf->MultiCell(0,6, iconv("UTF-8", "ISO-8859-1", "con ".$documento_tipo." Nº. ".$documento_numero." y domicilio en la calle ".strtoupper($direccion_calle)." Nº. ".$direccion_numero.$direccion_proc." con teléfono Nº. ".$telefonoFinal." solicita a PARQUE SERENIDAD S.R.L. la otorgación del servicio de ".$prestacion_tipo." de quien en vida fuera:"));
	
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(0,10, $difunto,0,0,'C');
	$pdf->Ln();
	$pdf->SetFont('Arial','I',10);
	$pdf->MultiCell(0,6, iconv("UTF-8", "ISO-8859-1", "Así también, acepta hacerse cargo de los costos establecidos en la solicitud de SERVICIOS en caso que tal derecho no correspondiere o tuviere cobertura parcial."));
	$pdf->MultiCell(0,6, iconv("UTF-8", "ISO-8859-1", "La presente tiene calidad de CONTRATO de acuerdo a las disposiciones del Código Civil Paraguayo al cual se compromete como tal."));
	$pdf->Cell(0,3, "",0,0,'C');
	$pdf->SetFont('Arial','',8);
	$pdf->Ln();
	$pdf->Cell(60,7, "OBSERVACIONES: ___________________________________________________________________________________________",0,0,'L');
	$pdf->Ln();
	$pdf->Cell(60,7, "____________________________________________________________________________________________________________",0,0,'L');
	$pdf->Ln();
	$pdf->Cell(60,7, "____________________________________________________________________________________________________________",0,0,'L');
	$pdf->Ln();
	$pdf->Cell(0,6, "",0,0,'C');
	$pdf->Ln();
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(90,7, "________________________________",0,0,'L');
	$pdf->Cell(90,7, "________________________________",0,0,'L');
	$pdf->Ln();
	$pdf->SetFont('Arial','I',10);
	$pdf->Cell(90,5, iconv("UTF-8", "ISO-8859-1", ucwords($coordinador)),0,0,'L');
	$pdf->Cell(90,5, iconv("UTF-8", "ISO-8859-1", ucwords($titular_firma)),0,0,'L');
	$pdf->Ln();
	$pdf->Cell(90,5, "Coordinador",0,0,'L');
	
//	if(!empty($autorizador) and $autorizador != "sin datos")
	if(isset($_POST['firmante']) and strtolower($_POST['firmante']) == "autorizador")
	{
		$pdf->Cell(90,5, "Autorizante",0,0,'L');
	}
	else
	{
		$pdf->Cell(90,5, "Titular",0,0,'L');
	}
	$pdf->Ln();
	$pdf->SetFont('Arial','I',8);
	// $pdf->Cell(90,5, "",0,0,'L');
	if(ctype_digit($coordinador_ced)) $coordinador_ced = number_format($coordinador_ced,0,",",".");
	$pdf->Cell(90,5, iconv("UTF-8", "ISO-8859-1", trim("Cedula Nº: ".$coordinador_ced)),0,0,'L');
	// $pdf->Cell(90,5, iconv("UTF-8", "ISO-8859-1", trim($documento_tipo." Nº: ".$documento_numero)),0,0,'L');
	$pdf->Cell(90,5, iconv("UTF-8", "ISO-8859-1", trim(str_replace("Cedula", "Cédula", $documento_tipo_firma)." Nº: ".$documento_numero_firma)),0,0,'L');
	$pdf->Cell(90,5, "",0,0,'L');
	$pdf->Ln();
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(170,5, "USO INTERNO",'T',0,'L');
	$pdf->Ln();
	// $pdf->Ln();
	$ind = 0;
	foreach($_SESSION['campos'] as $campo_nombre => $campo_atributos)
	{
		$otorgado = explode("_", $campo_nombre);
		if($campo_nombre != "creado" and $campo_nombre != "modificado" and $campo_nombre != "borrado" and ($otorgado[0] == "otorgacion") or $otorgado[0] == "otd")
		{
			$pdf->SetFont('Arial','B',8);
			if(strpos($datos[$campo_nombre]['rotulo'], "ano") !== FALSE) $datos[$campo_nombre]['rotulo'] = str_ireplace("ano", "año", $datos[$campo_nombre]['rotulo']);
			if(strpos($datos[$campo_nombre]['rotulo'], "otd") !== FALSE) $datos[$campo_nombre]['rotulo'] = str_ireplace("otd", "otorgacion", $datos[$campo_nombre]['rotulo']);
			$pdf->Cell(48,7, iconv("UTF-8", "ISO-8859-1", trim(ucwords(str_replace("_"," ",$datos[$campo_nombre]['rotulo'])))),0,0,'L');
			$pdf->Cell(4,7, ": ",0,0,'L');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(40,7, iconv("UTF-8", "ISO-8859-1", "________________"),0,0,'L');
			if($ind %2)
			{
				$pdf->Ln();
			}
			$ind++;		
		}		
	}
	$pdf->Cell(90,8, "",0,0,'L');
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(1,5, " ",0,0,'L');	
	$pdf->Cell(44,5, "_________________________",0,0,'L');
	$pdf->Cell(44,5, "_________________________",0,0,'L');
	$pdf->Cell(44,5, "_________________________",0,0,'L');
	$pdf->Cell(44,5, "_________________________",0,0,'L');
	$pdf->Ln();		
	$pdf->Cell(1,5, " ",0,0,'L');	
	$pdf->Cell(44,5, iconv("UTF-8", "ISO-8859-1", "Aclaración:________________"),0,0,'L');
	$pdf->Cell(44,5, iconv("UTF-8", "ISO-8859-1", "Aclaración:________________"),0,0,'L');
	$pdf->Cell(44,5, iconv("UTF-8", "ISO-8859-1", "Aclaración:________________"),0,0,'L');
	$pdf->Cell(44,5, iconv("UTF-8", "ISO-8859-1", "Aclaración:________________"),0,0,'L');
	// $pdf->Cell(44,5, "Fernando Ibarra",0,0,'L');
	$pdf->Ln();		
	$pdf->Cell(1,5, " ",0,0,'L');		
	$pdf->Cell(44,5, "",0,0,'L');
	// $pdf->Cell(44,5, "Servicios",0,0,'L');
	// $pdf->Cell(44,4, "Gerencia Operativa",0,0,'L');
	// $pdf->Cell(44,4, iconv("UTF-8", "ISO-8859-1", "Administración"),0,0,'L');
	// $pdf->Cell(44,4, "Gerente General",0,0,'L');
	// $pdf->Ln();
}
$pdf->Output();
?>