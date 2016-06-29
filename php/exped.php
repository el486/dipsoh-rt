<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Consulta de Expedientes de la oficina</title>
<meta http-equiv="content-type" content="text/html; charset=LATIN1">

    <!-- ** CSS ** -->
    <!-- base library -->
    <link rel="stylesheet" type="text/css" href="../css/ext-all.css" />
	<link rel="stylesheet" type="text/css" href="../css/xtheme-blue.css" />
	
			<style type="text/css">
		html,body{
			margin:20;
			padding:10px 20px 10px 20px;
			font:normal 18px arial,tahoma, helvetica, sans-serif;
		}

		.digitsi .x-grid3-cell { 
			background-color: #ffe2e2; 
			font:normal 12px arial,tahoma, helvetica, sans-serif;
			color: #000; 
		} 
		 
		.digitno .x-grid3-cell { 
			background-color: #e2ffe2;
			font:normal 12px arial,tahoma, helvetica, sans-serif;
			color: #000; 
		}
		
		.x-grid3-cell-inner, .x-grid3-hd-inner {
		  white-space: normal; /* changed from nowrap */
		}
		
		</style>

    <!-- ExtJS library: base/adapter -->
    <script type="text/javascript" src="../js/ext-base.js"></script>

    <!-- ExtJS library: all widgets -->
    <script type="text/javascript" src="../js/ext-all.js"></script>
	<script src="../js/Exporter-all.js" type="text/javascript"></script>
	<script type="text/javascript" src="../js/ext-lang-es.js"></script>
	<!-- extensions -->
	<link rel="stylesheet" type="text/css" href="../js/gridfilters/css/RangeMenu.css" />
	<link rel="stylesheet" type="text/css" href="../js/gridfilters/css/GridFilters.css" />
	<script type="text/javascript" src="../js/gridfilters/menu/RangeMenu.js"></script>
	<script type="text/javascript" src="../js/gridfilters/menu/ListMenu.js"></script>
	
	<script type="text/javascript" src="../js/gridfilters/GridFilters.js"></script>
	<script type="text/javascript" src="../js/gridfilters/filter/Filter.js"></script>
	<script type="text/javascript" src="../js/gridfilters/filter/StringFilter.js"></script>
	<script type="text/javascript" src="../js/gridfilters/filter/DateFilter.js"></script>
	<script type="text/javascript" src="../js/gridfilters/filter/ListFilter.js"></script>
	<script type="text/javascript" src="../js/gridfilters/filter/NumericFilter.js"></script>
	<script type="text/javascript" src="../js/gridfilters/filter/BooleanFilter.js"></script>

<script>
    data=<?php
		include("class_lib.php");
		$base = new ConexionDB ("E:\\DatosGis\\Planos\\expedientes.accdb");
		
		$expClass = new ParserExpedientes($base,$carpeta);
		echo $expClass->getJsonData();
	?>;
	
function linkExpedientes(val){
var val2=val.replace('/','-').replace(' Alc:','-').replace(' Cpo:','-').replace(' ','');
var array=val2.split('-');
return '<a href="http://sistemas.gba.gov.ar/consulta/expedientes/movimientos.php?caract='+array[0]+'&nroexp='+array[1]+'&anioexp='+array[2]+'&alcance='+array[3]+'&nrocuerpo='+array[4]+'" target="_blank">'+val+'</a>'
}

Ext.onReady(function(){
	//console.log(data);
    
	var reader = new Ext.data.ArrayReader({}, [
		   {name: 'num_exp'},
		   {name: 'tipo'},
		   {name: 'iniciador'},
		   {name: 'extracto'},
		   {name: 'ubicacion'},
		   {name: 'numpart'},
		   {name: 'partido'},
		   {name: 'catastral'},
		   {name: 'partida'},
		   {name: 'exppal'},
		   {name: 'obra'},
		   {name: 'ofi'},
		   {name: 'fechaact'},
		   {name: 'obs'}
    ]);
    
	var store = new Ext.data.GroupingStore({
			id:'lotGroupStore',
			reader: reader,
			//groupOnSort: true,
			//remoteSort: true,
			groupField: 'num_exp',
			data:data
			});
	
	var vista = new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})',
			getRowClass: function(record) { 
						return record.get('ofi') == '0' ? 'digitsi' : 'digitno'; 
						} 
        });
		
	var filters = new Ext.ux.grid.GridFilters({
        // encode and local configuration options defined previously for easier reuse
        encode: false, // json encode the filter query
        local: true,   // defaults to false (remote filtering)
        filters: [{
            type: 'string',
            dataIndex: 'num_exp'
        }, {
            type: 'string',
            dataIndex: 'iniciador'
        }, {
            type: 'string',
            dataIndex: 'extracto'
        }, {
            type: 'numeric',
            dataIndex: 'numpart'
        },{
            type: 'numeric',
            dataIndex: 'partida'
        }, {
            type: 'string',
            dataIndex: 'obra'
        }, {
            type: 'string',
            dataIndex: 'tipo'
        }, {
            type: 'string',
            dataIndex: 'catastral'
        },{
            type: 'string',
            dataIndex: 'partido'
        },{
            type: 'string',
            dataIndex: 'ubicacion'
        }
		
		/*, {
            type: 'list',
            dataIndex: 'size',
            options: ['small', 'medium', 'large', 'extra large'],
            phpMode: true
        }, {
            type: 'boolean',
            dataIndex: 'visible'
        }*/]
    });    
	
	var grid = new Ext.grid.GridPanel({
        store: store,
		plugins: [filters],
		columnLines: true,
		tbar:[],
		//cls: 'custom-grid', 
		id:'CargaDWG',
        columns: [
            {header: "NumExp", width: 100, dataIndex: 'num_exp', sortable: true, filterable: true,
			renderer: function (val,params,record) {
				return linkExpedientes(val);
				}
			},
			{header: "Tipo", width: 100, dataIndex: 'tipo', sortable: true, filterable: true},
			{header: "Iniciador", width: 150, dataIndex: 'iniciador', sortable: true, filterable: true},
			{header: "Extracto", width: 200, dataIndex: 'extracto', sortable: true, filterable: true},
			{header: "Ubicacion", width: 100, dataIndex: 'ubicacion', sortable: true, filterable: true},
			{header: "Partido", width: 50, dataIndex: 'numpart', sortable: true, filterable: true},
			{header: "Nombre", width: 100, dataIndex: 'partido', sortable: true, filterable: true},
			{header: "Nomenclatura", width: 150, dataIndex: 'catastral', sortable: true, filterable: true},
			{header: "Partida", width: 50, dataIndex: 'partida', sortable: true, filterable: true},
			{header: "Obra", width: 200, dataIndex: 'obra', sortable: true, filterable: true},
			{header: "Observaciones", width: 200, dataIndex: 'obs', sortable: true, filterable: true},
			{header: "Exped.Ppal", width: 100, dataIndex: 'exppal', sortable: true, filterable: true},
			{header: "Actualizado", width: 100, dataIndex: 'fechaact', sortable: true, filterable: true},
			{header: "Ofi", width: 20, dataIndex: 'ofi', sortable: true, filterable: true}
			
        ],
		view: vista,
		fbar  : [
		{
            text: 'Limpiar Filtros',
            handler: function () {
                grid.filters.clearFilters();
            } 
        },
		{
            text:'Desagrupar',
            handler : function(){
                store.clearGrouping();
            }
        },
		{
            text:'Colapsar todo',
            handler : function(){
                vista.collapseAllGroups();
            }
        },
		{
            text:'Expandir todo',
            handler : function(){
                vista.expandAllGroups();
            }
        }
		
		],
        height:700
    });
	
	var exportButton = new Ext.ux.Exporter.Button({
	component: grid,
	text     : "Descargar todo como archivo de excel"
	});

	grid.getTopToolbar().add(exportButton);
	store.clearGrouping();
	grid.render('grid');
	//console.log(store);
	
	});


</script>

</head>
<body>
    <h1 id="title">Expedientes</h1>
	<br>
	<div id="grid"></div>
    <div id="infoDIV">
	Busquedas: CTRL+F <br>
	<br>
	<a href="http://192.168.1.13:8080/heron/rt/index.htm" target="_new">VOLVER AL MENU</a><br>
	<!--a href="http://192.168.1.13:8080/heron/rt/php/exped.php" target="_new">EXPEDIENTES</a><br-->
	<a href="http://192.168.1.13:8080/heron/rt/php/carpetas.php" target="_new">CARPETAS</a><br>
	<a href="http://www.mosp.gba.gov.ar/sig_hidraulica/planos/planos.asp" target="_blank">PLANOS FINALIZADOS</a><br>
	<br>
	<a href="http://www.mosp.gba.gov.ar/sig_hidraulica/visor/index.php" target="_blank">VISOR SIG DiPSOH</a><br>
	<br>
	<a href="http://sistemas.gba.gov.ar/consulta/expedientes/busqueda1.html" target="_new">CONSULTA PUBLICA POR NUMERO EXPEDIENTE</a><br>
	<a href="http://sistemas.gba.gov.ar/consulta/expedientes/busqueda2.html" target="_new">CONSULTA PUBLICA POR OTROS DATOS</a><br>
	</div>
	<br>
</body>
</html>