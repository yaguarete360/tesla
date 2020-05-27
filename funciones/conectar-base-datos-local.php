<?php

	//descomentar pa subir

//	error_reporting(0);

	
//testing

$con2=mysqli_connect("localhost","######","######") or die ("could not connect to mysql");

//$con= mysqli_connect("$db_host","$db_username","$db_pass") or die ("could not connect to mysql"); 

mysqli_select_db($con2,"parquese_pse") or die ("no database"); 



/*
$con2=mysqli_connect("localhost","root","123456789");
mysql_set_charset('utf8',$con2);

mysql_select_db("tablas_vigentes",$con2);
*/
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