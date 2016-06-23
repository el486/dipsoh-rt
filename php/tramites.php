    <?php
	include("class_lib.php");
	$carpeta = $_REQUEST['carpeta'];
	$base = new ConexionDB ("E:\\DatosGis\\Planos\\baseRT_bs.mdb");
	if (is_null($carpeta)) $carpeta=0; 

	$tramitesClass = new ParserTramites($base,$carpeta);
	echo $tramitesClass->getHtmlData();
	?>