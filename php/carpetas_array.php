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
    <?php
		include("class_lib_array.php");
		$base = new ConexionDB ("E:\\DatosGis\\Planos\\baseRT_bs.mdb");
		$carpetasClass = new ParserCarpetas($base,$carpeta);
		$listramClass = new ParserListaTramites($base,$carpeta);
		?>;
	data= <?php echo $carpetasClass->getJsonData(); ?>;
	listaTramites= <?php echo $listramClass->getJsonData();?>;
function linkExpedientes(val){
	var val2=val.replace('/','-').replace(' Alc:','-').replace(' Cpo:','-').replace(' ','');
	var array=val2.split('-');
	return '<a href="http://sistemas.gba.gov.ar/consulta/expedientes/movimientos.php?caract='+array[0]+'&nroexp='+array[1]+'&anioexp='+array[2]+'&alcance=0&nrocuerpo=1" target="_blank">'+val+'</a>'
}

function date2string(date){
	if (date==''){
	return '0';
	} else {
	return '%27'+(date.getMonth()+1)+'/'+date.getDate()+'/'+date.getFullYear()+'%27';
	}
}
function parseCheck(value){
	if (value){
	return '-1';
	} else {
	return '0';
	}
}
	
function popupTramites(value){
    var win;
	var url='tramites.php?carpeta='+value;
	Ext.Ajax.request({
    url:url,
    success: function(response){
        // response.responseText will have your html content
		if(!win){
				win = new Ext.Window({
					title:'Tramites de la carpeta '+value,
					id:'winTramites',
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
							win.close();
						}
					},{
						text     : 'Agregar Tramite',
						handler  : function(){
							agregarTramite(value);
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
							win.close();
						}
					}]
				});
				win.show();
			} 
	  }
	});
}

function agregarTramite(carpeta){
	var win;
	if(!win){
			win = new Ext.Window({
				title:'Alta de tramites carpeta '+carpeta,
				width: 200,
				height: 250,
				closeAction :'hide',
				modal: true, 
				items: [ {
					xtype: 'panel',
					width: '100%',
					items:[
								{
									xtype: "label",
									html: 'Tramite.<br>',
									style: {
										fontSize: '12px',
										color: '#AAAAAA'
									}
								},
								{	
									xtype:'combo',
									fieldLabel: '  Tramite',
									id: "codTramite",
									typeAhead: true,
									width: 125,
									triggerAction: 'all',
									lazyRender:true,
									mode: 'local',
									store: new Ext.data.ArrayStore({
										id: 0,
										fields: [
											'myId',
											'displayText'
										],
										data: listaTramites
										//data:[["1","CIRCULAR 10"],["2","INFORME DE DOMINIO"],["3","VISADO CPA"],["4","VISADO CECOD"],["5","VISADO MUNICIPAL"],["6","ANTECEDENTES CATASTRALES"],["7","VISADO VIALIDAD"],["8","VISADO DPE"],["9","VISADO ADA"],["10","REGISTRACION CATASTRO"],["11","REGISTRACION REGISTRO PROPIEDAD"],["12","VISADO GPS"],["13","VISADO ASUNTOS AGRARIOS"],["14","VISADO GEODESIA"],["15","PRESENTACION DEFINITIVO"],["16",null],["17","ASIENTO REGISTRAL"]]

									}),
									valueField: 'myId',
									displayField: 'displayText'
								},
								{
									xtype: "label",
									html: 'Fecha ingreso.<br>',
									style: {
										fontSize: '12px',
										color: '#AAAAAA'
									}
								},							
								{
									xtype: "datefield",
									id: "fechaIng",
									value: new Date()																							
								},
								{
									xtype: "label",
									html: 'Fecha Aprobacion.<br>',
									style: {
										fontSize: '12px',
										color: '#AAAAAA'
									}
								},
								{
									xtype: "datefield",
									id: "fechaAprob"
								},
								{
									xtype: "label",
									html: 'Aprobado.<br>',
									style: {
										fontSize: '12px',
										color: '#AAAAAA'
									}
								},
								{
									xtype: "checkbox",
									id: "checkAprob"																							
								},
								{
									xtype: "label",
									html: 'Observaciones.<br>',
									style: {
										fontSize: '12px',
										color: '#AAAAAA'
									}
								},
								{
									xtype: "textfield",
									id: "observaciones"																							
								}
						],
						buttons: [{
							text: 'Guardar',
							listeners: {
								click: function () {
									var url='editarTramite.php?accion=nuevo&carpeta='+carpeta
									+'&tramite='+Ext.getCmp('codTramite').getValue()
									+'&fini='+date2string(Ext.getCmp('fechaIng').getValue())
									+'&ffin='+date2string(Ext.getCmp('fechaAprob').getValue())
									+'&aprob='+parseCheck(Ext.getCmp('checkAprob').checked)
									+'&obs='+Ext.getCmp('observaciones').getValue();
									//alert(url);
									Ext.Ajax.request({
									url:url,
									success: function(response){
										var text=response.responseText;
										if (text=='') text='Agregado con exito'; 
										alert(text);
										} 											 
									});
									win.close();
									Ext.getCmp('winTramites').close();
									popupTramites(carpeta);
								}
							}
						},{
						text     : 'Cerrar',
						handler  : function(){
							win.close();
							}
						}]
				}]
			});
			win.show();
		}
   //   }
	//});
}

function editTramite(id,carpeta,fecha_pedido){
	var win;
	if(!win){
			win = new Ext.Window({
				title:'Edicion de tramite '+id ,
				width: 200,
				height: 220,
				closeAction :'hide',
				modal: true, 
				items: [ {
					xtype: 'panel',
					width: '100%',
					items:[
								{
									xtype:"button",
									text     : 'Borrar Tramite '+id,
									handler  : function(){
										var url='editarTramite.php?accion=borrar&id='+id;
										//alert(url);
										Ext.Ajax.request({
										url:url,
										success: function(response){
											var text=response.responseText;
											if (text=='') text='Eliminado con exito'; 
											alert(text);
											} 											 
										});
										win.close();
										Ext.getCmp('winTramites').close();
										popupTramites(carpeta);
										}
								},
								{
									xtype: "label",
									html: '<br>Fecha Pedido: '+fecha_pedido+'.<br>Fecha Recibido:<br>',
									style: {
										fontSize: '12px',
										color: '#AAAAAA'
									}
								},
								{
									xtype: "datefield",
									id: "fechaRec",
									value: new Date()																							
								},
								{
									xtype: "label",
									html: 'Aprobado.<br>',
									style: {
										fontSize: '12px',
										color: '#AAAAAA'
									}
								},
								{
									xtype: "checkbox",
									id: "checkAprob"																							
								},
								{
									xtype: "label",
									html: 'Observaciones.<br>',
									style: {
										fontSize: '12px',
										color: '#AAAAAA'
									}
								},
								{
									xtype: "textfield",
									id: "observaciones"																							
								}
						],
						buttons: [{
							text: 'Guardar',
							listeners: {
								click: function () {
									var url='editarTramite.php?accion=editar&id='+id
									+'&ffin='+date2string(Ext.getCmp('fechaRec').getValue())
									+'&aprob='+parseCheck(Ext.getCmp('checkAprob').checked)
									+'&obs='+Ext.getCmp('observaciones').getValue();
									//alert(url);
									Ext.Ajax.request({
									url:url,
									success: function(response){
										var text=response.responseText;
										if (text=='') text='Modificado con exito'; 
										alert(text);
										} 											 
									});
									win.close();
									Ext.getCmp('winTramites').close();
									popupTramites(carpeta);
								}
							}
						},{
						text     : 'Cerrar',
						handler  : function(){
							win.close();
							}
						}]
				}]
			});
			win.show();
		}
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