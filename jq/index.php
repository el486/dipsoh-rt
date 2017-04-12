<?php
include("..\php\class_lib_array.php");
$carpeta = $_REQUEST['carpeta'];
$base = new ConexionDB ("E:\\DatosGis\\Planos\\baseRT_bs.mdb");
if (is_null($carpeta)){
	$carpeta=0;
	$carpetasClass = new ParserCarpetas($base,$carpeta);
	$salida = str_replace('"Num_carpeta"','"recid"',$carpetasClass->getJsonDataFull());
	}else{
	$tramitesClass = new ParserTramites($base,$carpeta);
	$salida = str_replace('"Num_carpeta"','"recid"',$tramitesClass->getJsonDataFull());	
	}
echo $salida;

?>