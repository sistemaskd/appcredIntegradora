
var sucursal_consultar_reader = new Ext.data.JsonReader({     
                root: 'data',
              },[
                {name: 'codigo', type: 'int', mapping: 'codigo'},
                {name: 'nombre', type: 'string', mapping: 'nombre'},
                ]);

var sucursal_consultar_store = new Ext.data.Store({
                proxy: new Ext.data.HttpProxy({
                        url: BASE_URL+'/catalog/consultar-sucursal',
                         method: 'POST'
                    }), 
                reader : sucursal_consultar_reader,
                sortInfo:{field: 'codigo', direction: "ASC"}
               })

// El cargado debe hacerse hasta querer ver la información
//solicitud_consultar_store.load({params:{start:0,limit:10}});

var ConsultarSucursal = new Ext.FormPanel({
	id:'consultarsucursal-panel',
	title:'Consultar Sucursal', 
    width: 900,
    labelAlign: 'top',
    frame:true,
    bodyStyle:'padding:5px 5px 0',
    items: [{
    	xtype: 'grid',
    	id: 'grid_sucursal',
    	title:'Sucursal', 
    	bodyStyle:'padding:5px 5px 0',
    	store: sucursal_consultar_store,  
    	renderTo: document.body,  
    	columns: [  
           {id:'codigo',header: "# de Sucursal", width: 100, sortable: true, dataIndex: 'codigo'},  
           {id:'nombre',header: "Nombre Sucursal", width: 150, sortable: true, dataIndex: 'nombre'},
          
           {
               xtype: 'actioncolumn',
               width: 50,
               items: [{
                   icon   : PATH_CSS+'/images/images/page_white_edit.png',  // Use a URL in the icon config
                   tooltip: 'Editar Sucursal',
                   handler: function(grid, rowIndex, colIndex) {
            	   		/*var rec = usuario_consultar_store.getAt(rowIndex);
            	   		var id_usuario = rec.get('id_usuario');
            	   		
            	   		params = {};
            	   		params['id_usuario'] = id_usuario;
            	   		
            	   		solicitud_editar_window.show(params);*/
                   }
               }]
           },{
               xtype: 'actioncolumn',
               width: 50,
               items: [{
                   icon   : PATH_CSS+'/images/images/page_white_magnify.png',  // Use a URL in the icon config
                   tooltip: 'Visualizar Sucursal',
                   handler: function(grid, rowIndex, colIndex)
                   {
            	   		var rec = sucursal_consultar_store.getAt(rowIndex);
            	   		var codigo = rec.get('codigo');
            	   		
            	   		Ext.Ajax.request({
            	   		    url: BASE_URL+'/catalog/get-informative-sucursal/codigo/'+codigo,
            	   		    params: {sections:'sucursal'}, 
            	   		    method:'POST',
            	   		    success: function(response){
            	   		    	
            	   		    	data = Ext.util.JSON.decode(response.responseText);
            	   		    	var paneles = new Ext.TabPanel({
                    	   			activeTab: 0,
                    	   			items:
                    	   				[{
                    	   					title:"Sucursal",
                    	   					html: data.sucursal
                    	   				},]
                    	   		});
                    	   		
                    	   		var sucursal_visualizar_window = new Ext.Window({
                    	   			title: 'Codigo #'+codigo,
                    	   			resizable: false,
                    	   			modal: true,
                    	   			width: 500,
                    	   			height: 400,
                    	   			items: [paneles]
                    	   		});
                    	   		
                    	   		sucursal_visualizar_window.show();
            	   		    	
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
            store: sucursal_consultar_store,
            displayInfo: true,
        })
     }]
});


