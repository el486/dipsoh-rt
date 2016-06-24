</head>
<body>
<?php
include("class_lib.php");
$carpeta = $_REQUEST['carpeta'];
$base = new ConexionDB ("E:\\DatosGis\\Planos\\baseRT_bs.mdb");
$baseExp = new ConexionDB ("E:\\DatosGis\\Planos\\expedientes.accdb");
if (is_null($carpeta)) $carpeta=0; 

$carpetasClass = new ParserCarpetas($base,$carpeta);
echo $carpetasClass->getJsonData();

echo '<br><br>';

$tramitesClass = new ParserTramites($base,$carpeta);
//var_dump($tramitesClass);
echo $tramitesClass->getHtmlData();	
		
echo $tramitesClass->getJsonData();	

$nomenClass = new ParserNomencla($base,$carpeta);
//var_dump($tramitesClass);
echo $nomenClass->getHtmlData();	
		
echo $nomenClass->getJsonData();	

$expClass = new ParserExpedientes($baseExp,$carpeta);
echo $expClass->getJsonData();
?>
</body>
</html>