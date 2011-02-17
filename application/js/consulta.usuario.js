
var usuario_consultar_reader = new Ext.data.JsonReader({     
                root: 'data',
              },[
                {name: 'id_usuario', type: 'int', mapping: 'id_usuario'},
                {name: 'login', type: 'string', mapping: 'login'},
                {name: 'nombre', type: 'string', mapping: 'nombre'},
                {name: 'apellido_paterno', type: 'string', mapping: 'apellido_paterno'},
                {name: 'apellido_materno', type: 'string', mapping: 'apellido_materno'},
                {name: 'email', type: 'string', mapping: 'email'},
                {name: 'rol', type: 'string', mapping: 'rol'},
                ]);

var usuario_consultar_store = new Ext.data.Store({
                proxy: new Ext.data.HttpProxy({
                        url: BASE_URL+'/catalog/consultar-usuario',
                         method: 'POST'
                    }), 
                reader : usuario_consultar_reader,
                sortInfo:{field: 'id_usuario', direction: "ASC"}
               })

// El cargado debe hacerse hasta querer ver la información
//solicitud_consultar_store.load({params:{start:0,limit:10}});

var ConsultarUsuario = new Ext.FormPanel({
	id:'consultarusuario-panel',
	title:'Consultar usuario', 
    width: 900,
    labelAlign: 'top',
    frame:true,
    bodyStyle:'padding:5px 5px 0',
    items: [{
    	xtype: 'grid',
    	id: 'grid_usuario',
    	title:'Usuario', 
    	bodyStyle:'padding:5px 5px 0',
    	store: usuario_consultar_store,  
    	renderTo: document.body,  
    	columns: [  
           {id:'id_usuario',header: "# de usuario", width: 100, sortable: true, dataIndex: 'id_usuario'},  
           {id:'login',header: "Login", width: 150, sortable: true, dataIndex: 'login'},
           {id:'nombre',header: "Nombre", width: 150, sortable: true, dataIndex: 'nombre'},
           {id:'apellido_paterno',header: "Apellido Paterno", width: 150, sortable: true, dataIndex: 'apellido_paterno'},
           {id:'apellido_materno',header: "Apellido Materno", width: 150, sortable: true, dataIndex: 'apellido_materno'},
           {id:'email',header: "Email", width: 150, sortable: true, dataIndex: 'email'},
           {id:'rol',header: "Rol", width: 150, sortable: true, dataIndex: 'rol'},
           {
               xtype: 'actioncolumn',
               width: 50,
               items: [{
                   icon   : PATH_CSS+'/images/images/edit.gif',  // Use a URL in the icon config
                   tooltip: 'Editar Usuario',
                   handler: function(grid, rowIndex, colIndex) {
            	   		var rec = usuario_consultar_store.getAt(rowIndex);
            	   		var id_usuario = rec.get('id_usuario');
            	   		
            	   		params = {};
            	   		params['id_usuario'] = id_usuario;
            	   		
            	   		usuario_editar_window.show(params);
                   }
               }]
           },{
               xtype: 'actioncolumn',
               width: 50,
               items: [{
                   icon   : PATH_CSS+'/images/images/view.gif',  // Use a URL in the icon config
                   tooltip: 'Visualizar Usuario',
                   handler: function(grid, rowIndex, colIndex)
                   {
            	   		var rec = usuario_consultar_store.getAt(rowIndex);
            	   		var id_usuario = rec.get('id_usuario');
            	   		
            	   		Ext.Ajax.request({
            	   		    url: BASE_URL+'/catalog/get-informative/id_usuario/'+id_usuario,
            	   		    params: {sections:'usuario'},
            	   		    method:'POST',
            	   		    success: function(response){
            	   		    	
            	   		    	data = Ext.util.JSON.decode(response.responseText);
            	   		    	var paneles = new Ext.TabPanel({
                    	   			activeTab: 0,
                    	   			items:
                    	   				[{
                    	   					title:"Usuario",
                    	   					html: data.usuario
                    	   				},]
                    	   		});
                    	   		
                    	   		var usuario_visualizar_window = new Ext.Window({
                    	   			title: 'Usuario #'+id_usuario,
                    	   			resizable: false,
                    	   			modal: true,
                    	   			width: 500,
                    	   			height: 400,
                    	   			items: [paneles]
                    	   		});
                    	   		
                    	   		usuario_visualizar_window.show();
            	   		    	
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
            store: usuario_consultar_store,
            displayInfo: true,
        })
     }]
});


usuario_editar_window = {};
usuario_editar_window.show = function(params)
{
	
	var id_usuario = params['id_usuario'];
	
	var editarusuario = new Ext.form.FormPanel({
		id: 'editarusuario',
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
				name: 'rol',
				hiddenName: 'rol',
			    fieldLabel:'Estatus',
				 displayField:'etiqueta',
                 valueField: 'valor',
				allowBlank: false,
				id:'rol',
				triggerAction:'all',
				store: storeBuilder.createStore('rol',{useCase:'usuario',field:'rol'})
			}],
			buttons:[{ 
                text:'Actualizar',
                formBind: true,
                handler:function(){
					var forma = Ext.getCmp('editarusuario').getForm();
					if(forma.isValid()){
						forma.submit({
							url: BASE_URL+'/catalog/update-status/id_usuario/'+id_usuario,
							method:'POST',
							waitTitle:'Actualizando...', 
							waitMsg:'Espere un momento...',
							success:function(form, action){
								var obj = Ext.util.JSON.decode(action.response.responseText);
								var callback = function(){
									Ext.getCmp('rol_window').close();
									usuario_consultar_store.reload();
								}
								Ext.Msg.alert('Exito', obj.message, callback);
							}
						}); 	
					}
            	}
            }],
	});

	var win = new Ext.Window({
		id: 'rol_window',
		title: 'Actualizar Datos del usuario',
		width:600,
		height:250,
		modal: true,
		bodyStyle: 'padding:10px;background-color:#fff',
		items: [editarusuario]
	});
	
	win.show();
}



