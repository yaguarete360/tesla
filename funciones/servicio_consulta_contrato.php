<?php
error_reporting(0);

include 'conectar-base-datos-ocean.php';

$contrato_numero = $_POST['numero_contrato'];
$contrato_nombre = $_POST['nombre_contrato'];
$contrato_fecha_inicio = $_POST['fecha_inicio_contrato'];
$contrato_fecha_fin = $_POST['fecha_fin_contrato'];
$contrato_centro = $_POST['centro_contrato'];



$query = "SELECT a.CONTRA_NUM as NRO_CONTRATO,a.CONTRA_TIT AS CUENTA_NRO,
CONCAT(a.CONTRA_CEN,' ',a.CONTRA_NUM) as CONTRATO,a.CONTRA_CEN as CENTRO, CONTRA_NOM as 
NOMBRE,REPLACE(REPLACE(CONTRA_EST,'V','VIGENTE'),'B', 'BAJA') AS ESTADO,CONTRA_PLA AS CATEGORIA,DATE_FORMAT(CONTRA_FEC,'%d-%m-%Y') AS FECHA, b.NOMBRE as ASOCIACION,
b.NUMERO as NRO_ASOCIACION,c.COBRAD_NUM AS NRO_COBRADOR,c.COBRAD_NOM as NOMBRE_COBRADOR,d.APELNOM AS VENDEDOR,d.NUMERO AS NRO_VENDEDOR 
FROM contra_m as a
left join asocia_m as b on a.CONTRA_ASO = b.NUMERO
left join cobrad_m as c on  a.CONTRA_COB = c.COBRAD_NUM
left join vendedor_vista as d on a.CONTRA_VEN_NRO = d.numero 
where  
a.CONTRA_ASO = b.NUMERO  ";

if($contrato_numero !=''){
        $query.= " and a.CONTRA_NUM = $contrato_numero";
}

if($contrato_nombre !=''){
        $query.= " and a.CONTRA_NOM like '%$contrato_nombre%' ";
}

if($contrato_centro !=''){
        $query.= " and a.CONTRA_CEN like '%$contrato_centro%' ";
}

if($contrato_numero ==''){
        if($contrato_fecha_inicio !='' && $contrato_fecha_inicio !=''){
                $query.= "and a.CONTRA_FEC between '$contrato_fecha_inicio' and '$contrato_fecha_fin' ";
        }
}
//echo $query;
//die();
$result = mysqli_query($con,$query) or die(mysqli_error());

$parent = array() ;

	while($row = mysqli_fetch_array($result))
	{
                $parent['NRO_CONTRATO'] = $row['NRO_CONTRATO'];
                $parent['CUENTA_NRO'] = $row['CUENTA_NRO'];
                $parent['CONTRATO'] = $row['CONTRATO'];
                $parent['NOMBRE'] = $row['NOMBRE'];
                $parent['ESTADO'] = $row['ESTADO'];
                $parent['CATEGORIA'] = $row['CATEGORIA'];
                $parent['FECHA'] = $row['FECHA'];
                $parent['ASOCIACION'] = $row['ASOCIACION'];
                $parent['NRO_ASOCIACION'] = $row['NRO_ASOCIACION'];
                $parent['NOMBRE_COBRADOR'] = $row['NOMBRE_COBRADOR'];
                $parent['NRO_COBRADOR'] = $row['NRO_COBRADOR'];
                $parent['VENDEDOR'] = $row['VENDEDOR'];
                $parent['NRO_VENDEDOR'] = $row['NRO_VENDEDOR'];
                $parent['CENTRO'] = $row['CENTRO'];
                
                
                
                

                $parent['detalles'] = array();

		 
		$query1 = "SELECT
				benefi_num AS ORD,
				benefi_nom AS NOMBRE_APELLIDO,
				BENEFI_BAJ AS MOTIVO,
                                CONCAT('DE BAJA: ',' ',DATE_FORMAT(BENEFI_FBA,'%d-%m-%Y'),' ',BENEFI_BAJ) as BAJA,
                                CONCAT(BENEFI_TDO,' ',BENEFI_NDO) as DOCUMENTO,
                                DATE_FORMAT(BENEFI_FNA,'%d-%m-%Y') as FECHA_NACIMIENTO,
                                BENEFI_EDA AS EDAD,
                                DATE_FORMAT(BENEFI_FVI,'%d-%m-%Y') AS VIGENCIA,
                                REPLACE(REPLACE(BENEFI_EST,'V','VIGENTE'),'B', 'BAJA') AS ESTADO,
                                (case WHEN  BENEFI_EST ='V'
                                THEN 
                                BENEFI_MTO
                                WHEN  BENEFI_EST ='B'
                                THEN 
                                0
                                END) as CUOTA
                              


			FROM
				benefi_d_oracle
			WHERE BENEFI_CON = '$row[NRO_CONTRATO]'
                        ORDER BY benefi_num ASC" ;
                        
                        //echo $query1;

		$result1 = mysqli_query($con,$query1) or die(mysqli_error());
	
		while($row1 = mysqli_fetch_array($result1))
		{

                         $detail=array();       
                        $detail['ORD'] = $row1['ORD'];
                        $detail['NOMBRE_APELLIDO'] = $row1['NOMBRE_APELLIDO'];
                        $detail['MOTIVO'] = $row1['MOTIVO'];
                        $detail['BAJA'] = $row1['BAJA'];
                        $detail['DOCUMENTO'] = $row1['DOCUMENTO'];
                        $detail['FECHA_NACIMIENTO'] = $row1['FECHA_NACIMIENTO'];
                        $detail['EDAD'] = $row1['EDAD'];
                        $detail['VIGENCIA'] = $row1['VIGENCIA'];
                        $detail['ESTADO'] = $row1['ESTADO'];
                        $detail['CUOTA'] = $row1['CUOTA'];
                         
                        array_push($parent['detalles'],$detail);

			//$parent[] = array("ORD"=>$row1['ORD'],"NOMBRE_APELLIDO"=>$row1['NOMBRE_APELLIDO']);
		}
	}
	

     echo  json_encode($parent); 


?>