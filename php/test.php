</head>
<body>
<?php
include("class_lib.php");
$carpeta = $_REQUEST['carpeta'];
$campo = $_REQUEST['campo'];
$filtro = $_REQUEST['filtro'];
$base = new ConexionDB ("E:\\DatosGis\\Planos\\baseRT_bs.mdb");
$baseExp = new ConexionDB ("E:\\DatosGis\\Planos\\expedientes.accdb");
if (is_null($carpeta)) $carpeta=0; 
/*
$carpetasClass = new ParserCarpetas($base,$carpeta);
echo $carpetasClass->getJsonData();
echo '<br><br>';
$array = $carpetasClass->getArrayData();
var_dump($array);
echo '<br><br>';

$tramitesClass = new ParserTramites($base,$carpeta);
echo $tramitesClass->getHtmlData();	
echo '<br><br>';
echo $tramitesClass->getJsonData();	
echo '<br><br>';

$nomenClass = new ParserNomencla($base,$carpeta);
echo $nomenClass->getHtmlData();	
echo '<br><br>';		
echo $nomenClass->getJsonData();	
echo '<br><br>';
*/
$expClass = new ParserExpedientes($baseExp,$carpeta);
echo $expClass->getJsonData();
echo '<br><br>';

$array2 = $expClass->getArrayData();
//var_dump($array2);

function filtroPartidos($array,$campo,$filtro){
	foreach ($array as $elemento) {
	if ($elemento[$campo]==$filtro) $salida[]=$elemento;
	}
	return $salida;
}

echo '<br>campo: '.$campo.'<br>filtro: '.$filtro.'<br><br>';
$filtro=filtroPartidos($array2,$campo,$filtro);
var_dump($filtro);
?>
</body>
</html>