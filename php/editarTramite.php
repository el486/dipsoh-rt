<?php
//uso: http://192.168.1.13:8080/heron/rt/php/editarTramite.php?carpeta=30&tramite=6&fini=1/1/2000&ffin=1/1/2100&aprob=0&obs=nada
//sin fecha recibido: http://192.168.1.13:8080/heron/rt/php/editarTramite.php?carpeta=30&tramite=9&fini=1/1/2000&ffin=0&aprob=0&obs=nada1
include("class_lib_array.php");
$id=$_REQUEST['id'];
$carpeta = $_REQUEST['carpeta'];
$tramite = $_REQUEST['tramite'];
$fechaini = $_REQUEST['fini'];
$fechafin = $_REQUEST['ffin'];
$aprobado = $_REQUEST['aprob'];
$obs = $_REQUEST['obs'];
$accion = $_REQUEST['accion'];
$base = new ConexionDB ("E:\\DatosGis\\Planos\\baseRT_bs.mdb");
//echo $accion;
$tram= new Tramite($base);

if ($accion=='nuevo'){ 
//echo 'nuevo';
$insertTram = $tram->insertar($carpeta,$tramite,$fechaini,$fechafin,$aprobado,$obs);
}
if ($accion=='editar'){ 
//echo $id;
$editTram = $tram->modificar($id,$fechafin,$aprobado,$obs);
}
if ($accion=='borrar'){ 
//echo $id;
$deleteTram = $tram->borrar($id);
}
?>