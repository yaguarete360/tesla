<?php
  $contrato = $_POST['contrato'];
  
  echo '<br><br>';
 
   echo '<div class="rectangulo">';
   echo '<div class="datagrid">';
   echo '<td><strong>&nbsp;NUMERO DE CUENTA: </strong>'.$contrato['CUENTA_NRO'].'</td>&nbsp;&nbsp;&nbsp;';    
   echo '<td><strong>NUMERO DE CONTRATO: </strong>'.$contrato['NRO_CONTRATO'].'</td>&nbsp;&nbsp;&nbsp;';
       echo '<td><strong>CONTRATO: </strong>'.$contrato['CONTRATO'].'</td>&nbsp;&nbsp;&nbsp;';
    
      echo '<td>'.$contrato['NOMBRE'].'</td><br>';
      echo '<td><strong>&nbsp;ESTADO: </strong>'.$contrato['ESTADO'].'&nbsp;&nbsp;&nbsp;</td>';
      echo '<td><strong>CATEGORIA: </strong>'.$contrato['CATEGORIA'].'&nbsp;&nbsp;&nbsp;</td>';
      echo '<td><strong>FECHA: </strong>'.$contrato['FECHA'].'</td><br>';
      echo '<td><strong>&nbsp;ASOCIACION: </strong>'.$contrato['ASOCIACION'].'</td>';
      echo '<td>('.$contrato['NRO_ASOCIACION'].')</td><br>';
      echo '<td><strong>&nbsp;NOMBRE COBRADOR: </strong>'.$contrato['NOMBRE_COBRADOR'].'</td>';
      echo '<td>('.$contrato['NRO_COBRADOR'].')</td><br>';
      echo '<td><strong>&nbsp;VENDEDOR: </strong>'.$contrato['VENDEDOR'].'</td>';
      echo '<td>('.$contrato['NRO_VENDEDOR'].')</td><br>';
       
      echo '</div>';
      echo '<td style="font-size:24px;color:red;"><strong>BENEFICIARIOS</strong></td><br>';
      echo '<div class="datagrid">';
      echo '<table>';
      echo '<tr>';
      echo '<th><strong>NOMBRE Y APELLIDO</strong></th>';
      echo '<th><strong>DOCUMENTO</strong></th>';
      echo '<th><strong>BAJA</strong></th>';
      echo '<th><strong>FECHA DE NACIMIENTO</strong></th>';
      echo '<th><strong>EDAD</strong></th>';
      echo '<th><strong>VIGENCIA</strong></th>';
      echo '<th><strong>ESTADO</th>';
      echo '<th><strong>CUOTA</th>';
   echo '</tr>';   
   $total=0;
      foreach ($contrato['detalles'] as $value) 
      {
        echo '<tr>';
          echo '<td>'.$value['NOMBRE_APELLIDO'].'</td>';
          echo '<td>'.$value['DOCUMENTO'].'</td>';
          echo '<td>'.$value['BAJA'].'</td>';
          echo '<td>'.$value['FECHA_NACIMIENTO'].'</td>';
          echo '<td>'.$value['EDAD'].'</td>';
          echo '<td>'.$value['VIGENCIA'].'</td>';
          echo '<td>'.$value['ESTADO'].'</td>';
          echo '<td>'.number_format($value['CUOTA'], 0, '', '.').'</td>';
       echo '</tr>';   
       $total = $total + $value['CUOTA'];
      }
      echo '</table>';
      echo '</div>';


      echo '<div class="datagrid">';
      echo '<table>';
      echo '<tr>';
      echo '<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>';
      echo '<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>';
      echo '<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>';
      echo '<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>';
      echo '<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>';
      echo '<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>';
      echo '<th>CUOTA A PAGAR POR ESTE '.$contrato['CENTRO'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </th>';
      echo '<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($total, 0, '', '.').'</th>';
   echo '</tr>';
   echo '</div>';



 	echo '</div>';

?>

<style type="text/css">
	
	.rectangulo {
     width: 100%; 
     height: 100%; 
     
  }

  .cta{
  	margin-left: 20px;
  	margin-top: 8px;
  }


.datagrid table { border-collapse: collapse; text-align: left; width: 100%; } .datagrid {font: normal 12px/150% Arial, Helvetica, sans-serif; background: #fff; overflow: hidden; border: 1px solid #006699; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; }.datagrid table td, .datagrid table th { padding: 3px 10px; }.datagrid table thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; color:#FFFFFF; font-size: 15px; font-weight: bold; border-left: 1px solid #0070A8; } .datagrid table thead th:first-child { border: none; }.datagrid table tbody td { color: #00496B; border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal; }.datagrid table tbody .alt td { background: #E1EEF4; color: #00496B; }.datagrid table tbody td:first-child { border-left: none; }.datagrid table tbody tr:last-child td { border-bottom: none; }.datagrid table tfoot td div { border-top: 1px solid #006699;background: #E1EEF4;} .datagrid table tfoot td { padding: 0; font-size: 12px } .datagrid table tfoot td div{ padding: 2px; }.datagrid table tfoot td ul { margin: 0; padding:0; list-style: none; text-align: right; }.datagrid table tfoot  li { display: inline; }.datagrid table tfoot li a { text-decoration: none; display: inline-block;  padding: 2px 8px; margin: 1px;color: #FFFFFF;border: 1px solid #006699;-webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; }.datagrid table tfoot ul.active, .datagrid table tfoot ul a:hover { text-decoration: none;border-color: #006699; color: #FFFFFF; background: none; background-color:#00557F;}div.dhtmlx_window_active, div.dhx_modal_cover_dv { position: fixed !important; }

</style>