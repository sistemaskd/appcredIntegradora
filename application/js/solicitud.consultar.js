
var solicitud_consultar_reader = new Ext.data.JsonReader({     
                root: 'data',
              },[
                {name: 'id_solicitud', type: 'int', mapping: 'id_solicitud'},
                {name: 'nombre', type: 'string', mapping: 'nombre'},
                {name: 'apellido_paterno', type: 'string', mapping: 'apellido_paterno'},
                {name: 'apellido_materno', type: 'string', mapping: 'apellido_materno'},
                {name: 'codigo_sucursal', type: 'int', mapping: 'codigo_sucursal'},
                {name: 'monto_credito', type: 'float', mapping: 'monto_credito'}
                ]);

var solicitud_consultar_store = new Ext.data.Store({
                proxy: new Ext.data.HttpProxy({
                        url: BASE_URL+'/solicitud/consultar',
                        method: 'POST'
                    }), 
                reader : solicitud_consultar_reader,
                sortInfo:{field: 'id_solicitud', direction: "ASC"}
               })

// El cargado debe hacerse hasta querer ver la información
//solicitud_consultar_store.load({params:{start:0,limit:10}});

var Consultar = new Ext.FormPanel({
	id:'consultar-panel',
	title:'Consultar Solicitud', 
    width: 900,
    labelAlign: 'top',
    frame:true,
    bodyStyle:'padding:5px 5px 0',
    items: [{
    	xtype: 'grid',
    	id: 'grid_consultar',
    	title:'Consulta', 
    	bodyStyle:'padding:5px 5px 0',
    	store: solicitud_consultar_store,  
    	renderTo: document.body,  
    	columns: [  
           {id:'id_solicitud',header: "# de solicitud", width: 100, sortable: true, dataIndex: 'id_solicitud'},  
           {id:'nombre',header: "Nombre", width: 150, sortable: true, dataIndex: 'nombre'},
           {id:'apellido_paterno',header: "Apellido paterno", width: 150, sortable: true, dataIndex: 'apellido_paterno'},
           {id:'apellido_materno',header: "Apellido materno", width: 150, sortable: true, dataIndex: 'apellido_materno'},
           {id:'codigo_sucursal',header: "Codigo de sucursal", width: 150, sortable: true, dataIndex: 'codigo_sucursal'},
           {id:'monto_credito',header: "Monto del credito", width: 150, sortable: true, dataIndex: 'monto_credito'},
           {
               xtype: 'actioncolumn',
               width: 50,
               items: [{
                   icon   : PATH_CSS+'/images/images/report_edit.png',  // Use a URL in the icon config
                   tooltip: 'Editar Solicitud',
                   handler: function(grid, rowIndex, colIndex) {
            	   		var rec = solicitud_consultar_store.getAt(rowIndex);
            	   		var id_solicitud = rec.get('id_solicitud');
            	   		
            	   		params = {};
            	   		params['id_solicitud'] = id_solicitud;
            	   		
            	   		solicitud_editar_window.show(params);
                   }
               }]
           },
           {
               xtype: 'actioncolumn',
               width: 50,
               items: [{
                   icon   : PATH_CSS+'/images/images/report_magnify.png',  // Use a URL in the icon config
                   tooltip: 'Visualizar Solicitud',
                   handler: function(grid, rowIndex, colIndex)
                   {
            	   		var rec = solicitud_consultar_store.getAt(rowIndex);
            	   		var id_solicitud = rec.get('id_solicitud');
            	   		
            	   		Ext.Ajax.request({
            	   		    url: BASE_URL+'/solicitud/get-informative/id_solicitud/'+id_solicitud,
            	   		    params: {sections:'cliente,empleo,referencia,credito'},
            	   		    method:'POST',
            	   		    success: function(response){
            	   		    	
            	   		    	data = Ext.util.JSON.decode(response.responseText);
            	   		    	var paneles = new Ext.TabPanel({
                    	   			activeTab: 0,
                    	   			items:
                    	   				[{
                    	   					title:"Cliente",
                    	   					html: data.cliente
                    	   				},{
                    	   					title:"Empleo",
                    	   					html: data.empleo
                    	   				},{
                    	   					title:"Referencia",
                    	   					html: data.referencia
                    	   				},{
                    	   					title:"Credito",
                    	   					html: data.credito
                    	   				}]
                    	   		});
                    	   		
                    	   		var solicitud_visualizar_window = new Ext.Window({
                    	   			title: 'Solicitud #'+id_solicitud,
                    	   			resizable: false,
                    	   			modal: true,
                    	   			width: 500,
                    	   			height: 400,
                    	   			items: [paneles]
                    	   		});
                    	   		
                    	   		solicitud_visualizar_window.show();
            	   		    	
            	   		    }
            	   		})
                   }
               }]
           }

        ],  
        height:320,
        stripeRows: true,
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: solicitud_consultar_store,
            displayInfo: true,
        })
     }]
});

var store_solicitud_estatus = storeBuilder.createStore('solicitud_estatus',{useCase:'solicitud',field:'estatus'});

solicitud_editar_window = {};
solicitud_editar_window.show = function(params)
{
	
	var id_solicitud = params['id_solicitud'];
	
	var form = new Ext.form.FormPanel({
		id: 'forma_estatus',
		width:270,
		bodyStyle:'margin-left:10px;',
		border:false,
		labelWidth: 80,
		defaults: {
			xtype:'textfield',
			width:150
		},
		items:
			[{
				xtype:'combo',
				name: 'estatus',
				hiddenName: 'estatus',
				displayField:'etiqueta',
				valueField: 'valor',
				fieldLabel:'Estatus',
				allowBlank: false,
				id:'estatus',
				triggerAction:'all',
				store: store_solicitud_estatus
			}],
			buttons:[{ 
                text:'Actualizar',
                formBind: true,
                handler:function(){
					var forma = Ext.getCmp('forma_estatus').getForm();
					if(forma.isValid()){
						forma.submit({
							url: BASE_URL+'/solicitud/update-status/id_solicitud/'+id_solicitud,
							method:'POST',
							waitTitle:'Actualizando...', 
							waitMsg:'Espere un momento...',
							success:function(form, action){
								var obj = Ext.util.JSON.decode(action.response.responseText);
								var callback = function(){
									Ext.getCmp('status_window').close();
									solicitud_consultar_store.reload();
								}
								Ext.Msg.alert('Exito', obj.message, callback);
							}
						}); 	
					}
            	}
            }],
	});

	var win = new Ext.Window({
		id: 'status_window',
		title: 'Editar estatus de la solicitud',
		width:600,
		height:250,
		modal: true,
		bodyStyle: 'padding:10px;background-color:#fff',
		items: [form]
	});
	
	win.show();
}

