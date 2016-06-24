    <?php
		include("class_lib.php");
		$base = new ConexionDB ("E:\\DatosGis\\Planos\\baseRT_bs.mdb");
		$carpeta = $_REQUEST['carpeta'];
		if (is_null($carpeta)) $carpeta=0; 
		
		$nomenClass = new ParserNomencla($base,$carpeta);
		echo $nomenClass->getHtmlData();
	?>