
var report_consultar_reader = new Ext.data.JsonReader({     
                root: false,
              },[
                {name: 'numero_solicitud', type: 'int', mapping: 'numero_solicitud'},
                {name: 'codigo', type: 'int', mapping: 'codigo'},
                {name: 'codigo_vendedor', type: 'int', mapping: 'codigo_vendedor'}
          
                ]);

var report_consultar_store = new Ext.data.Store({
                proxy: new Ext.data.HttpProxy({
                        url: BASE_URL+'/report/report-vend',
                        method: 'POST'
                    }), 
                reader : report_consultar_reader,
                sortInfo:{field: 'codigo', direction: "ASC"}
               });

// El cargado debe hacerse hasta querer ver la información
//solicitud_consultar_store.load({params:{start:0,limit:10}});

var Report_Venta_Vendedor = new Ext.FormPanel({
	id:'reportacomuladozona-panel',
	title: 'Reporte de Venta X Vendedor',
    width: 900,
    labelAlign: 'top',
    frame:true,
    bodyStyle:'padding:5px 5px 0',
    items: [{
    	xtype: 'grid',
    	id: 'grid_vendedor',
    	title:'Reporte Vendedor', 
    	bodyStyle:'padding:5px 5px 0',
    	store: report_consultar_store,  
    	renderTo: document.body,  
    	columns: [  
           {id:'numero_solicitud',header: "# de solicitud", width: 100, sortable: true, dataIndex: 'numero_solicitud'},  
           {id:'codigo',header: "Codigo", width: 150, sortable: true, dataIndex: 'codigo'},
           {id:'codigo_vendedor',header: "Vendedor", width: 150, sortable: true, dataIndex: 'codigo_vendedor'}
           
        ],  
        height:320,
        stripeRows: true,
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: report_consultar_store,
            displayInfo: true,
        })
     }]
});
/*
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
}*/
