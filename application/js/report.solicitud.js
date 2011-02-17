var  ReportedeVentaZona = new Ext.FormPanel({
	id: 'reportzonaventa-panel',
	title: 'Reporte de Venta X Vendedor',
	url: BASE_URL+'/pdf/report-vend-cliente', 
	method:'POST',
	params: {sections:'ReporteVentaporCliente'},
	width: 900,
    labelAlign: 'top',
    frame:true,
    bodyStyle:'padding:5px 5px 0',
    items: [{
    	
            xtype:'datefield',
            fieldLabel: 'Fecha Inicio',
            name: 'fecha_inicio',
            anchor:'60%',
            format: 'Y-m-d',
            editable: false
                
        },{
            xtype:'datefield',
            fieldLabel: 'Fecha Final',
            name: 'fecha_final',
            anchor:'60%',
            format: 'Y-m-d',
            editable: false
                
        },{
        	width: 275,
            title: 'Sucursal',
           xtype:  'combo',
           mode:   'local',
          triggerAction:  'all',
           forceSelection: true,
           editable:      true,
           allowBlank: false,
           fieldLabel: 'Sucursales', 
           name: 'sucursales',
           hiddenName: 'codigo',
           displayField:'etiqueta', 
           valueField: 'valor',
           store: storeBuilder.createStore('reportzonaventa',{useCase:'sucursal_usuario_report',field:'nombre'})
           
     }   	
    	
    ],
    buttons: [{
        text: 'Consultar',
        id: 'btreportcosulta',
        handler: function(){ 
    	ReportedeVentaZona.getForm().submit({ 
   	    success: function(response){
    		alert(response);
   			
    		//data = Ext.util.JSON.decode(response.responseText);
    		/*var paneles = new Ext.TabPanel({
	   			activeTab: 0,
	   			items:
	   				[{
	   					title:"ReporteVentaporCliente",
	   					html: data.ReporteVentaporCliente
	   				},]
	   		});
	   		
	   		var usuario_visualizar_window = new Ext.Window({
	   			title: 'Reporte Venta X Cliente #',
	   			id: 'usuario_visualizar_window',
	   			resizable: false,
	   			modal: true,
	   			width: 500,
	   			height: 400,
	   			items: [paneles]
	   		});
	   		
	   		usuario_visualizar_window.show();*/
   			
    		
    		
                   }

               });
   			
    }
    }]
	
	
	
})
/*
var ReportSolicitudes = new Ext.FormPanel({
	//id:'reportzona-panel',
	title:'Reporte Venta', 
    width: 900,
    labelAlign: 'top',
    frame:true,
    bodyStyle:'padding:5px 5px 0',
    items: [{
    	xtype: 'grid',
    	title:'Reporte Venta Solicitudes', 
    	bodyStyle:'padding:5px 5px 0',
    	store: report_solicitud_store,  
    	renderTo: document.body,  
    	columns: [  
           {id:'numero_solicitud',header: "Total Solicitud", width: 100, sortable: true, dataIndex: 'numero_solicitud'},  
           {id:'fecha_creacion',header: "Fecha Creacion", width: 150, sortable: true, dataIndex: 'fecha_creacion'}
          
        ],  
        height:320,
        stripeRows: true,
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: report_solicitud_store,
            displayInfo: true,
        })
     }]
});*/