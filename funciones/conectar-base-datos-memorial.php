<?php

	//descomentar pa subir

	error_reporting(E_ALL);

	
//testing
//$con=mysqli_connect("memorial.lcompras.biz:3333","root","admin") or die ("could not connect to mysql");
//201.217.38.137

$con= mysqli_connect("localhost","#####","######") or die ("could not connect to mysql"); 
mysqli_select_db($con, "parquese_backup_temporal_memorial") or die ("no database"); 

//$con=mysqli_connect("localhost","root","123456789") or die ("could not connect to mysql");


//mysqli_select_db($con, "tablas_vigentes_15_12_2017") or die ("no database"); 


//$con2 = mysqli_connect('memorial.lcompras.biz:3333', 'root', 'admin')or die ("could not connect to mysql");

 
//$con= mysqli_connect("201.217.38.137:3333", "root", "admin", "tablas_vigentes");
//mysqli_set_charset('utf8',$con);

//mysqli_select_db("tablas_vigentes",$con);


  

 

?>

 



