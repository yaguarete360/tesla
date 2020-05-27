<?php

	//descomentar pa subir

//	error_reporting(0);

	
//testing
//$con=mysqli_connect("memorial.lcompras.biz:3333","root","admin") or die ("could not connect to mysql");


//$con= mysqli_connect("memorial.lcompras.biz:3333","root","admin") or die ("could not connect to mysql"); 
//mysqli_select_db($con, "tablas_vigentes") or die ("no database"); 



$con=mysqli_connect("159.203.83.97","#####","#######") or die ("could not connect to mysql");


mysqli_select_db($con,"tablas_vigentes") or die ("no database"); 

 


//$con= mysqli_connect("memorial.lcompras.biz:3333", "root", "admin", "tablas_vigentes");
//mysqli_set_charset('utf8',$con);

//mysql_select_db("tablas_vigentes",$con);
//testing

//produccion
/*
$con=mysql_connect("memorial.lcompras.biz:3333","root","admin");
mysql_set_charset('utf8',$con);

mysql_select_db("tablas_vigentes",$con);
*/

//producccion

////	

////	cristobal2010

////$con=mysql_connect("localhost","cristobal2010","cris2010");

////	mysql_select_db("sancristobal_db",$con);



//en carpeta neodat

//$con=mysql_connect("localhost","cristo_86_use","clave_cooperativa_1986*_12_100_09_08");

//mysql_select_db("neodat_cooperativa_base_2010_py_09",$con);



//dominio sancristobal

//$con=mysql_connect("localhost","cristo_86_use","clave_cooperativa_1986*_12_100_09_08");

//mysql_select_db("neodat_base_cristobal_db_2010",$con);

 

 

 

?>