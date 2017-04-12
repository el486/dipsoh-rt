</head>
<body>
<?php
include("class_lib_array.php");
$carpeta = $_REQUEST['carpeta'];
$campo = $_REQUEST['campo'];
$filtro = $_REQUEST['filtro'];
$partido = $_REQUEST['partido'];
$anio = $_REQUEST['anio'];
$base = new ConexionDB ("E:\\DatosGis\\Planos\\baseRT_bs.mdb");
$baseExp = new ConexionDB ("E:\\DatosGis\\Planos\\expedientes.accdb");
$baseCel = new ConexionDB ("E:\\DatosGis\\Planos\\Database101.mdb");
if (is_null($carpeta)) $carpeta=0; 

$insertTram = new InsertarTramite($base,30,6,'1/1/2007','1/1/2100',0,'nada');
//echo $insertTram;
echo '<br>';
echo $insertTram->sql;

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


$expClass = new ParserExpedientes($baseExp,$carpeta);
echo $expClass->getJsonData();
echo '<br><br>';

$array2 = $expClass->getArrayData();
var_dump($array2);

echo '<br>campo: '.$campo.'<br>filtro: '.$filtro.'<br><br>';
$filtro=$expClass->getArrayFilter($campo,$filtro);

foreach ($filtro as $elemento) {
		$suma+=$elemento["NumPart"];
		}
echo '<br>Suma: '.$suma.'<br>';
		
var_dump($filtro);
echo '<br><br>';
echo json_encode($filtro);

$celulares = new ControladorAccess($baseCel,"select * from lineas_por_grupo");
echo $celulares->get_json_data();
*/
?>
</body>
</html>