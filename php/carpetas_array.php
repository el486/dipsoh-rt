<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Consulta de carpetas abiertas</title>
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
	<script type="text/javascript" src="../js/Exporter-all.js"></script>
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
		include("class_lib_array.php");
		$base = new ConexionDB ("E:\\DatosGis\\Planos\\baseRT_bs.mdb");
		
		$carpetasClass = new ParserCarpetas($base,$carpeta);
		echo $carpetasClass->getJsonData();
	?>;
	
function linkExpedientes(val){
var val2=val.replace('/','-').replace(' Alc:','-').replace(' Cpo:','-').replace(' ','');
var array=val2.split('-');
return '<a href="http://sistemas.gba.gov.ar/consulta/expedientes/movimientos.php?caract='+array[0]+'&nroexp='+array[1]+'&anioexp='+array[2]+'&alcance=0&nrocuerpo=1" target="_blank">'+val+'</a>'
}
	
function popupTramites(value){
    
	var url='tramites.php?carpeta='+value;
	Ext.Ajax.request({
    url:url,
    success: function(response){
        // response.responseText will have your html content

		var win;
			if(!win){
				win = new Ext.Window({
					title:'Tramites de la carpeta '+value,
					width: 600,
					height: 300,
					closeAction :'hide',
					modal: true, 
					html: response.responseText,
					//overflow:'auto', 
					autoScroll:'true',
					buttons: [{
						text     : 'Cerrar',
						handler  : function(){
							win.hide();
						}
					}]
				});
				win.show();
			} 
	  }
	});
}

function popupNomencla(value){
    
	var url='nomencla.php?carpeta='+value;
	Ext.Ajax.request({
    url:url,
    success: function(response){
        // response.responseText will have your html content
		var win;
			if(!win){
				win = new Ext.Window({
					title:'Nomenclatura de la carpeta '+value,
					width: 300,
					height: 300,
					closeAction :'hide',
					modal: true, 
					html: response.responseText,
					//overflow:'auto', 
					autoScroll:'true',
					buttons: [{
						text     : 'Cerrar',
						handler  : function(){
							win.hide();
						}
					}]
				});
				win.show();
			} 
	  }
	});
}

Ext.onReady(function(){
	//console.log(data);
    
	var reader = new Ext.data.ArrayReader({}, [
		   {name: 'num_carpeta'},
		   {name: 'partido'},
		   {name: 'parcela'},
		   {name: 'obra'},
		   {name: 'prop'},
		   {name: 'exped'},
		   {name: 'obs'},
		   {name: 'numpla'},
		   {name: 'aprobado'},
		   {name: 'fin'}
		   
    ]);
    
	var store = new Ext.data.GroupingStore({
			id:'lotGroupStore',
			reader: reader,
			//groupOnSort: true,
			//remoteSort: true,
			groupField: 'num_carpeta',
			data:data
			});
	
	var vista = new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})',
			getRowClass: function(record) { 
						return record.get('fin') == '1' ? 'digitsi' : 'digitno'; 
						} 
        });
		
	var filters = new Ext.ux.grid.GridFilters({
        // encode and local configuration options defined previously for easier reuse
        encode: false, // json encode the filter query
        local: true,   // defaults to false (remote filtering)
        filters: [{
            type: 'string',
            dataIndex: 'num_carpeta'
        }, {
            type: 'string',
            dataIndex: 'exped'
        }, {
            type: 'string',
            dataIndex: 'parcela'
        }, {
            type: 'string',
            dataIndex: 'partido'
        }, {
            type: 'string',
            dataIndex: 'obra'
        },{
            type: 'string',
            dataIndex: 'prop'
        },{
            type: 'string',
            dataIndex: 'numpla'
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
            {header: "Carpeta", width: 50, dataIndex: 'num_carpeta', sortable: true, filterable: true,
			renderer: function (val,params,record) {
				return '<a href="javascript:popupTramites(\''+val+'\')">'+val+'</a>';
				}
			},
			{header: "Expediente", width: 150, dataIndex: 'exped', sortable: true, filterable: true,
			renderer: function (val,params,record) {
				if (val!=null){
					var arr=val.split(' ');
					var cadena='';
					for (index = 0; index < arr.length; ++index) {
						cadena+=linkExpedientes(arr[index])+'<br>';
						}
					return cadena;
					}
					else
					{return "-"}
				}
			},
			{header: "Partido", width: 50, dataIndex: 'partido', sortable: true, filterable: true},
			{header: "Parcela", width: 150, dataIndex: 'parcela', sortable: true, filterable: true,
			renderer: function (val,params,record) {
				return '<a href="javascript:popupNomencla(\''+record.get('num_carpeta')+'\')">'+val+'</a>';
				}
			},
			{header: "Obra", width: 300, dataIndex: 'obra', sortable: true, filterable: true},
			{header: "Propietario", width: 300, dataIndex: 'prop', sortable: true, filterable: true},
			{header: "Plano", width: 70, dataIndex: 'numpla', sortable: true, filterable: true,
			renderer: function (val,params,record) {
				if (val!=null){
					var arr=val.split(' ');
					var cadena='';
					for (index = 0; index < arr.length; ++index) {
						cadena+='<a href="http://www.mosp.gba.gov.ar/sig_hidraulica/planos/planos.asp?numpla='+arr[index]+'" target="_new">'+arr[index]+'</a><br>';
						}
					return cadena;
					}
					else
					{return "-";}					
				}
			},
			{header: "Aprobado", width: 70, dataIndex: 'aprobado', sortable: true,
				renderer: function (val,params,record) {
					if (val!=null){
						var val2=val.replace(' ','-');
						var array=val2.split('-');
						return array[2]+'/'+array[1]+'/'+array[0];
					}
					else return "-";
					}
			},
			{header: "Observaciones", width: 300, dataIndex: 'obs', sortable: true},
			{header: "Fin", width: 30, dataIndex: 'fin', sortable: true},
			
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
        height:600
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
    <h1 id="title">Carpetas</h1>
	<br>
	<div id="grid"></div>
<div id="infoDIV">
	<a href="http://192.168.1.13:8080/heron/rt/index.htm" target="_new">VOLVER AL MENU</a><br>
	<a href="http://192.168.1.13:8080/heron/rt/php/exped.php" target="_new">EXPEDIENTES</a><br>
	<!--a href="http://192.168.1.13:8080/heron/rt/php/carpetas.php" target="_new">CARPETAS</a><br-->
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