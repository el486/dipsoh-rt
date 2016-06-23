</head>
<body>
<?php
include("class_lib.php");
$carpeta = $_REQUEST['carpeta'];
$base = new ConexionDB ("E:\\DatosGis\\Planos\\baseRT_bs.mdb");
if (is_null($carpeta)) $carpeta=0; 

$carpetas = new ControladorAccess($base,"SELECT * FROM carpetas");
$carpetas->set_field_list('[["Num_carpeta","num"],["Partido","num"],["Parcela","txt"],["Obra","txt"],["Propietario","txt"],["Num_expediente","txt"],["Observaciones","txt"],["Num_plano","txt"],["Fecha_aprobacion","fecha"],["Finalizado","txt"]]');
echo $carpetas->get_json_data();

echo '<br><br>';

$tramitesClass = new ParserTramites($base,$carpeta);
//var_dump($tramitesClass);
echo $tramitesClass->getHtmlData();	
		
echo $tramitesClass->getJsonData();	

$nomenClass = new ParserNomencla($base,$carpeta);
//var_dump($tramitesClass);
echo $nomenClass->getHtmlData();	
		
//echo $nomenClass->getJsonData();	

?>
</body>
</html>