
var report_ventas_reader = new Ext.data.JsonReader({     
                root: false,
              },[
                {name: 'Numero_Solicitud', type: 'int', mapping: 'Numero_Solicitud'},
                {name: 'Fecha_Creacion', type: 'string', mapping: 'Fecha_Creacion'}
                
                ]);

var report_ventas_store = new Ext.data.Store({
                proxy: new Ext.data.HttpProxy({
                        url: BASE_URL+'/report/get-dato',
                        method: 'POST'
                    }), 
                reader : report_ventas_reader,
                sortInfo:{field: 'Fecha_Creacion', direction: "ASC"}
               });

report_ventas_store.load({params:{start:0,limit:10}});

var ReportVentasSolicitudes = new Ext.FormPanel({
	id:'reportsolicitud-panel',
	title:'Reporte Venta Solicitudes', 
    width: 900,
    labelAlign: 'top',
    frame:true,
    bodyStyle:'padding:5px 5px 0',
    items: [{
    	xtype: 'grid',
    	title:'Reporte Venta Solicitudes', 
    	bodyStyle:'padding:5px 5px 0',
    	store: report_ventas_store,  
    	renderTo: document.body,  
    	columns: [  
           {id:'Numero_Solicitud',header: "Total Solicitud", width: 100, sortable: true, dataIndex: 'Numero_Solicitud'},  
           {id:'Fecha_Creacion',header: "Fecha Creacion", width: 150, sortable: true, dataIndex: 'Fecha_Creacion'}
          
        ],  
        height:320,
        stripeRows: true,
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: report_ventas_store,
            displayInfo: true,
        })
     }]
});