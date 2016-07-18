    <?php
	include("class_lib_array.php");
	$base = new ConexionDB ("E:\\DatosGis\\Planos\\baseRT_bs.mdb");
	$carpeta = $_REQUEST['carpeta'];
	if (is_null($carpeta)) $carpeta=0; 

	$tramitesClass = new ParserTramites($base,$carpeta);
	echo $tramitesClass->getHtmlData();
	?>