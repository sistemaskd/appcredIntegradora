
var start = {
    id: 'start-panel',
    title: 'Kondinero',
    layout: 'fit',
    bodyStyle: 'padding:25px',
    contentEl: 'start-div'  
};



var cardNav = function(incr){
    var l = Ext.getCmp('solicitud-panel').getLayout();
    var i = l.activeItem.id.split('card-')[1];
    var next = parseInt(i, 10) + incr;
    l.setActiveItem(next);
    updateNavigator(next);
    
};

function updateNavigator(index)
{
		Ext.getCmp('card-prev').setDisabled(index==0);
	    Ext.getCmp('card-next').setDisabled(index==4);
	    Ext.getCmp('card-end').setDisabled(index<4);
}

function resetNavigator()
{
	Ext.getCmp('card-prev').setDisabled(true);
    Ext.getCmp('card-next').setDisabled(false);
    Ext.getCmp('card-end').setDisabled(true);
}

var forma_card_1 = new Ext.FormPanel({
	id:'forma_card_1',
    labelAlign: 'top',
    frame:true,
    url:BASE_URL+'/solicitud/wizard',
     bodyStyle:'padding:5px 5px 0',
     items: [{
        layout:'column',
        items:[{
            columnWidth:.5,
            layout: 'form',
            items: [{
            	xtype:'hidden',
            	id: 'wizard_step_1',
                name: 'wizard_step',
                value:'informacion_solicitante'
            },{
                xtype:'numberfield',
                fieldLabel: 'Folio',
                name: 'folio',
                anchor:'60%'
            },{
            	xtype:'textfield',
                fieldLabel: 'Nombres',
                name: 'nombre',
                anchor:'60%',
                allowBlank:false,
                maskRe: /^[a-zA-Z_\xE1\xE9\xED\xF3\xFA\xC1\xC9\xCD\xD3\xDA\xD1\xF1\x2E\x2D\s]+$/,
                blankText:"Campo Nombre Requerido"
            },{
                xtype:'textfield',
                fieldLabel: 'Apellido Paterno',
                name: 'apellido_paterno',
                anchor:'60%',
                allowBlank:false,
                maskRe: /^[a-zA-Z_\xE1\xE9\xED\xF3\xFA\xC1\xC9\xCD\xD3\xDA\xD1\xF1\x2E\x2D\s]+$/,
                blankText:"Campo Apellido Paterno Requerido"
            },{
                xtype:'textfield',
                fieldLabel: 'Apellido Materno',
                name: 'apellido_materno',
                anchor:'60%',
                allowBlank:false,
       maskRe: /^[a-zA-Z_\xE1\xE9\xED\xF3\xFA\xC1\xC9\xCD\xD3\xDA\xD1\xF1\x2E\x2D\s]+$/,
                blankText:"Campo Apellido Materno Requerido"
        },
        {
            xtype:'datefield',
            fieldLabel: 'Fecha Nacimiento',
            name: 'fecha_nacimiento',
            anchor:'60%',
            format: 'Y-m-d',
            editable: false
                
        },{
            xtype:'textfield',
            fieldLabel: 'RFC',
            name: 'rfc',
            anchor:'60%',
            allowBlank:false,
            vtype: 'alphanum',
            blankText:"Campo RFC Requerido"
        },
{



 width: 275,

        xtype:  'combo',
        mode:   'local',
        value:  '1',
        triggerAction:  'all',
        forceSelection: true,
        editable:       false,
        allowBlank: false,
        fieldLabel: 'Nacionalidad',
        name: 'nacionalidad',
        hiddenName: 'nacionalidad',
        displayField:'name',
        valueField: 'value',
        store:          new Ext.data.JsonStore({
                fields : ['name', 'value'],
                data   : [
                        {name : 'Mexicana',   value: '1'},
                        {name : 'Extranjero',  value: '2'}
                        ]
                        })




        },{
            xtype:'textfield',
            fieldLabel: 'Estado Nacimiento',
            name: 'estado_nacimiento',
   maskRe: /^[a-zA-Z_\xE1\xE9\xED\xF3\xFA\xC1\xC9\xCD\xD3\xDA\xD1\xF1\x2E\x2D\s]+$/,
            anchor:'60%'
        },{
        	 width: 275,
             xtype:  'combo',
             mode:   'local',
             triggerAction:  'all',
             forceSelection: true,
             allowBlank: false,
             fieldLabel: 'Estado Civil',
             name: 'estado_civil',
             hiddenName: 'estado_civil',
             displayField:'etiqueta',
             valueField: 'valor',
             store: storeBuilder.createStore('solicitud',{useCase:'cliente',field:'estado_civil'})

        },
{
            xtype:'numberfield',
            fieldLabel: 'Dependientes',
            name: 'dependientes',
            anchor:'60%'
        },{
            width: 275,

        xtype:  'combo',
        mode:   'local',
        triggerAction:  'all',
        forceSelection: true,
        allowBlank: false,
        fieldLabel: 'Tipo Propiedad',
        name: 'tipo_propiedad',
        hiddenName: 'tipo_propiedad',
        displayField:'etiqueta',
        valueField: 'valor',
        store: storeBuilder.createStore('solicitud',{useCase:'cliente',field:'tipo_propiedad'})
        },{
            xtype:'textfield',
            fieldLabel: 'Domicilio',
            name: 'domicilio',
            vtype: 'alphanum',
            anchor:'60%' 
        },{
            xtype:'textfield',
            fieldLabel: 'Colonia',
            name: 'colonia',
   maskRe: /^[a-zA-Z_\xE1\xE9\xED\xF3\xFA\xC1\xC9\xCD\xD3\xDA\xD1\xF1\x2E\x2D\s]+$/,
            anchor:'60%'
        }]
        },{
            columnWidth:.5,
            layout: 'form',
            items: [
		{
            width: 275,
            xtype:  'combo',
            mode:   'local',
            triggerAction:  'all',
            forceSelection: true,
            allowBlank: false,
            fieldLabel: 'Municipio',
            name: 'id_municipio',
            hiddenName: 'id_municipio',
            displayField:'etiqueta',
            valueField: 'valor',
            store: storeBuilder.createStore('solicitud',{useCase:'municipio',field:'nombre'})
        },{
            xtype:'numberfield',
            fieldLabel: 'Codigo Postal',
            name: 'codigo_postal',
            anchor:'60%'
        },{
            xtype:'textfield',
            fieldLabel: 'Poblacion',
            name: 'poblacion',
            maskRe: /^[a-zA-Z_\xE1\xE9\xED\xF3\xFA\xC1\xC9\xCD\xD3\xDA\xD1\xF1\x2E\x2D\s]+$/,
            anchor:'60%'
    },{
        xtype:'textfield',
        fieldLabel: 'Estado',
        name: 'estado',
        anchor:'60%'
    },{
            xtype:'numberfield',
            fieldLabel: 'A&ntilde;os Residencia',
            name: 'anos_residencia',
            anchor:'60%'
        },
{
            xtype:'numberfield',
            fieldLabel: 'Telefono',
            name: 'telefono',
            anchor:'60%'
        },{
            xtype:'numberfield',
            fieldLabel: 'Celular',
            name: 'celular',
            anchor:'60%'
        },{
            xtype:'textfield',
        	fieldLabel: 'Email',
            name: 'email',
            vtype:'email',
            emailText: 'No es un correo valido, ejemplo "user@example.com"',
            value:'default@kondinero.com',
            anchor:'60%'
           
        
        },{
            width: 275,
            title: 'Vendedor',
           xtype:  'combo',
           mode:   'local',
          triggerAction:  'all',
           forceSelection: true,
           editable:       true,
           allowBlank: false,
           fieldLabel: 'Vendedor',
           name: 'vendedor',
           hiddenName: 'vendedor',
           displayField:'etiqueta',
           valueField: 'valor',
           store: storeBuilder.createStore('solicitud',{useCase:'vendedor',field:'nombre'})
           
     }
	    ]
        }]
    }]
});



var forma_card_2 = new Ext.FormPanel({
	id:'forma_card_2',
     labelAlign: 'top',
    frame:true,
    url:BASE_URL+'/solicitud/wizard',
         bodyStyle:'padding:5px 5px 0',
   
    items: [{
        layout:'column',
        items:[{
	    columnWidth:.5,
            layout: 'form',
            items: [{
            	xtype:'hidden',
            	id: 'wizard_step_2',
                name: 'wizard_step',
                value:'informacion_empleo'
            },{
                 xtype: 'textfield',
                 name: 'dependencia_laboral',
                 anchor: '60%',
        maskRe: /^[a-zA-Z_\xE1\xE9\xED\xF3\xFA\xC1\xC9\xCD\xD3\xDA\xD1\xF1\x2E\x2D\s]+$/,
                 fieldLabel: 'Dependencia Labora'
             },{
                 xtype: 'textfield',
                 name: 'profesion',
                 anchor: '60%',
        maskRe: /^[a-zA-Z_\xE1\xE9\xED\xF3\xFA\xC1\xC9\xCD\xD3\xDA\xD1\xF1\x2E\x2D\s]+$/,
                 fieldLabel: 'Profesion'
             },{
             	 xtype: 'textfield',
                  name: 'puesto',
                  anchor: '60%',
         maskRe: /^[a-zA-Z_\xE1\xE9\xED\xF3\xFA\xC1\xC9\xCD\xD3\xDA\xD1\xF1\x2E\x2D\s]+$/,
                  fieldLabel: 'Puesto'
             },{
                 xtype: 'datefield',
                 name: 'fecha_ingreso',
                 anchor: '60%',
                 fieldLabel: 'Fecha Ingreso',
                 format: 'Y-m-d',
                 editable: false
             },{
                 xtype: 'textfield',
                 name: 'domicilio_empleo',
                 anchor: '60%',
                 vtype: 'alphanum',
                 fieldLabel: 'Domicilio'
             },{
                 xtype: 'textfield',
                 name: 'colonia_empleo',
                 anchor: '60%',
        maskRe: /^[a-zA-Z_\xE1\xE9\xED\xF3\xFA\xC1\xC9\xCD\xD3\xDA\xD1\xF1\x2E\x2D\s]+$/,
                 fieldLabel: 'Colonia'
             },{
                 xtype: 'textfield',
                 name: 'municipio_empleo',
                 anchor: '60%',
        maskRe: /^[a-zA-Z_\xE1\xE9\xED\xF3\xFA\xC1\xC9\xCD\xD3\xDA\xD1\xF1\x2E\x2D\s]+$/,
                 fieldLabel: 'Municipio'
             },{
                 xtype: 'numberfield',
                 name: 'codigo_postal_empleo',
                 anchor: '60%',
                 fieldLabel: 'Codigo Postal'
             },{
                 xtype: 'textfield',
                 name: 'estado_empleo',
                 anchor: '60%',
        maskRe: /^[a-zA-Z_\xE1\xE9\xED\xF3\xFA\xC1\xC9\xCD\xD3\xDA\xD1\xF1\x2E\x2D\s]+$/,
                 fieldLabel: 'Estado'
             },{
                 xtype: 'numberfield',
                 name: 'telefono_empleo',
                 anchor: '60%',
                 fieldLabel: 'Telefono'
             },{
                 xtype: 'numberfield',
                 name: 'antiguedad',
                 anchor: '60%',
                 fieldLabel: 'Antiguedad'
             
	    
        }]
    }]
	}]
});




var forma_card_3 = new Ext.FormPanel({
	id:'forma_card_3',
      labelAlign: 'top',
    frame:true,
            url:BASE_URL+'/solicitud/wizard',
       bodyStyle:'padding:5px 5px 0',
    items: [{
        layout:'column',
        items:[{
        	xtype:'hidden',
        	id: 'wizard_step_3',
            name: 'wizard_step',
            value:'referencias_personales'
        },{
	    columnWidth:.5,
            layout: 'form',
            items: [{ 
            xtype:'textfield',
            fieldLabel: 'Nombres',
            id: 'nombre_referencia1',
            name: 'nombre_referencia[]',
            anchor:'60%',
            maskRe: /^[a-zA-Z_\xE1\xE9\xED\xF3\xFA\xC1\xC9\xCD\xD3\xDA\xD1\xF1\x2E\x2D\s]+$/,
            allowBlank:false,
            blankText:"Campo Nombre Requerido"
        },{
            xtype:'textfield',
            fieldLabel: 'Apellidos',
            id: 'apellido_referencia1',
            name: 'apellido_referencia[]',
            anchor:'60%',
            maskRe: /^[a-zA-Z_\xE1\xE9\xED\xF3\xFA\xC1\xC9\xCD\xD3\xDA\xD1\xF1\x2E\x2D\s]+$/,
            allowBlank:false,
            blankText:"Campo Apellido Requerido"
        },{
            xtype:'numberfield',
            fieldLabel: 'Telefono',
            id: 'telefono_referencia1',
            name: 'telefono_referencia[]',
            anchor:'60%',
            allowBlank:false,
            blankText:"Campo Telefono Requerido"
        },{



 width: 275,

        xtype:  'combo',
        mode:   'local',
        value:  '1',
        triggerAction:  'all',
        forceSelection: true,
        editable:       false,
        allowBlank: false,
        id: 'referencia_tipo1',
        fieldLabel: 'Tipo Referencia',
        name: 'referencia_tipo[]',
        hiddenName: 'referencia_tipo[]',
        displayField:'name',
        valueField: 'value',
        store:          new Ext.data.JsonStore({
                fields : ['name', 'value'],
                data   : [
                        {name : 'Familiar',   value: '1'},
                        {name : 'Laboral',  value: '2'}
                        ]
                        })




        
        
	   
        }
	    ]}
	    ,{
            columnWidth:.5,
            layout: 'form',
            items: [
		{ 
           
	   
	   
    	xtype:'textfield',
        fieldLabel: 'Nombres',
        id: 'nombre_referencia',
        name: 'nombre_referencia[]',
        anchor:'60%',
        maskRe: /^[a-zA-Z_\xE1\xE9\xED\xF3\xFA\xC1\xC9\xCD\xD3\xDA\xD1\xF1\x2E\x2D\s]+$/,
        allowBlank:false,
        blankText:"Campo Nombre Requerido"
    },{
        xtype:'textfield',
        fieldLabel: 'Apellidos',
        id: 'apellido_referencia',
        name: 'apellido_referencia[]',
        anchor:'60%',
        maskRe: /^[a-zA-Z_\xE1\xE9\xED\xF3\xFA\xC1\xC9\xCD\xD3\xDA\xD1\xF1\x2E\x2D\s]+$/,
        allowBlank:false,
        blankText:"Campo Apellido Requerido"
    },{
        xtype:'numberfield',
        fieldLabel: 'Telefono',
        id: 'telefono_referencia',
        name: 'telefono_referencia[]',
        anchor:'60%',
        allowBlank:false,
        blankText:"Campo Telefono Requerido"
    },{



width: 275,

    xtype:  'combo',
    mode:   'local',
    value:  '1',
    triggerAction:  'all',
    forceSelection: true,
    editable:       false,
    allowBlank: false,
    id: 'Tipo Referencia',
    fieldLabel: 'Tipo Referencia',
    name: 'referencia_tipo[]',
    hiddenName: 'referencia_tipo[]',
    displayField:'name',
    valueField: 'value',
    store:          new Ext.data.JsonStore({
            fields : ['name', 'value'],
            data   : [
                    {name : 'Familiar',   value: '1'},
                    {name : 'Laboral',  value: '2'}
                    ]
                    })
    			
	   
	   
	    }
    ]
	}]
	}]
});





var forma_card_4 = new Ext.FormPanel({
	id:'forma_card_4',
    abelAlign: 'top',
    frame:true,
    url:BASE_URL+'/solicitud/wizard',
     bodyStyle:'padding:5px 5px 0',
    
    items: [{
        layout:'column',
        items:[{
            columnWidth:.5,
            layout: 'form',
            items: [{
            	xtype:'hidden',
            	id: 'wizard_step_4',
                name: 'wizard_step',
                value:'informacion_crediticia'
            },{ 
            
	    
                     width: 275,
                     xtype:  'combo',
                     mode:   'local',
                     triggerAction:  'all',
                     forceSelection: true,
                     allowBlank: false,
                     id: 'codigo_sucursal',
                     fieldLabel: 'Sucursal',
                     name: 'codigo_sucursal',
                     hiddenName: 'codigo_sucursal',
                     displayField:'etiqueta',
                     valueField: 'valor',
                     store: storeBuilder.createStore('solicitud',{useCase:'sucursal',field:'nombre'})
                     			},{
                     				width: 275,

                                    xtype:  'combo',
                                    mode:   'local',
                                    value:  '1',
                                    triggerAction:  'all',
                                    forceSelection: true,
                                    editable:       false,
                                    allowBlank: false,
                                    id: 'codigo_institucion',
                                    fieldLabel: 'Institucion',
                                    name: 'codigo_institucion',
                                    hiddenName: 'tipo',
                                    displayField:'name',
                                    valueField: 'value',
                                    store:          new Ext.data.JsonStore({
                                            fields : ['name', 'value'],
                                            data   : [
                                                    {name : 'SEC JALISCO',   value: '1'},
                                                    {name : 'Tlaquepaque',  value: '2'},
                                                    {name : 'Monterrey',   value: '1'},
                                                    {name : 'Boston',  value: '2'},
                                                    {name : 'Calzada',   value: '1'},
                                                    {name : 'Tlaquepaque',  value: '2'}, 
                                                    {name : 'Morelia1',   value: '1'},
                                                    {name : 'Tlaquepaque',  value: '2'}
                                                    ]
                                                    })
                                    			},{
                     xtype:'numberfield',
                     fieldLabel: 'Numero Empleado',
                     id: 'numero_empleado',
                     name: 'numero_empleado',
                     anchor:'60%',
                     allowBlank:false,
                     blankText:"Campo Empleado Requerido"
                 },{
                	 width: 275,

                     xtype:  'combo',
                     mode:   'local',
                     value:  '1',
                     triggerAction:  'all',
                     forceSelection: true,
                     editable:       false,
                     allowBlank: false,
                     id: 'codigo_financiera',
                     fieldLabel: 'Financiera',
                     name: 'codigo_financiera',
                     hiddenName: 'tipo',
                     displayField:'name',
                     valueField: 'value',
                     store:          new Ext.data.JsonStore({
                             fields : ['name', 'value'],
                             data   : [
                                     {name : 'BNP',   value: '1'},
                                     {name : 'Credito Real',  value: '2'},
                                     {name : 'Kondinero',   value: '1'}
                                                                          ]
                                     })
                     			
                     
	    
	    
        }]
        },{
            columnWidth:.5,
            layout: 'form',
            items: [
		{ 
             	xtype:'numberfield',
                 fieldLabel: 'Monto del Credito',
                 id: 'monto_credito',
                 name: 'monto_credito',
                 anchor:'60%',
                 allowBlank:false,
                 blankText:"Campo Monto Credito Requerido"
        }
	    ]
        }]
    }]
});




var cardWizard = {
    id:'solicitud-panel',
    title: 'Solicitud - Kondinero',
    layout:'card',
    activeItem: 0,
    bodyStyle: 'padding:25px',
    defaults: {border:false},

      bbar: ['->', {
        id: 'card-prev',
        text: 'Atras',
        handler: cardNav.createDelegate(this, [-1]),
        disabled: true
    },{
        id: 'card-next',
        text: 'Siguiente',
        handler: function(){
    		var current_id = parseInt(Ext.getCmp('solicitud-panel').getLayout().activeItem.id.split('card-')[1])+1;
    		var current_form = Ext.getCmp('forma_card_'+(current_id)).getForm();
	    	if(current_form.isValid()){
	    		current_form.submit({
	    			method:'POST',
	    			waitTitle:'Conectando...', 
	    			waitMsg:'Enviando datos...',
	    			success: cardNav.createDelegate(this, [1]),
	    			failure: function(form, action){ 
                    			if(action.failureType == 'server'){ 
                    				obj = Ext.util.JSON.decode(action.response.responseText); 
                    				Ext.Msg.alert('Error', obj.errors.reason); 
                    			}else{ 
                    				Ext.Msg.alert('Advertencia!', 'El servidor de autenticación es inalcanzable" : ' + action.response.responseText); 
                    			} 
                			 }
	    		});	
			} else {
				Ext.Msg.alert("Estatus", "Es necesario llenar los campos obligatorios");
			}
    	}
    },
    {
        id: 'card-prev',
        text: 'Atras',
        handler: cardNav.createDelegate(this, [-1]),
        disabled: true
    },{
    	id: 'card-end',
        text: 'Finalizar',
        handler: function(){
    				Ext.Ajax.request({
    					url : BASE_URL+'/solicitud/wizard', 
    					params : { 'wizard_step' : 'finalizar' },
    					method : 'POST',
					    success: function(response){
    								resetNavigator();
									obj = Ext.util.JSON.decode(response.responseText);
									for(i=1;i<=4;i++){
										Ext.getCmp('forma_card_'+i).getForm().reset();	
									}
									if(obj.message){
										var respuesta = function(response){
											 if(response == 'yes'){
												 Ext.getCmp('solicitud-panel').getLayout().setActiveItem(0);
											 } else {
												 Ext.getCmp('solicitud-panel').getLayout().setActiveItem(0);
												 Ext.getCmp('content-panel').getLayout().setActiveItem(0);	 
											 }
										}
										Ext.MessageBox.confirm('Confirmar', obj.message, respuesta);	
									} else {
										Ext.Msg.alert('Estatus', 'La solcitud fue creada');
									}
			 					}
    					});
    			 },
        disabled: true
    }
    ],
        items: [{
        id: 'card-0',
        xtype: 'panel',
        autoScroll:true,
        
        
        title: 'Informacion del Solicitante',
       
        items: [{
          
            items:
            	[{
                title: 'Cliente',
                layout: 'form',
                 border:false,
                items: [forma_card_1]
            	}]
		}]
        

    },
	{
        id: 'card-1',
        xtype: 'panel',
        title: 'Informacion Empleo',
        autoScroll:true,
        items: [{
          
            items:
            	[{
                title: 'Empleo',
                layout: 'form',
                border:false,
                items: [forma_card_2]
            	}]
		}]
        

    },
           	            
            	{
        id: 'card-2',
        xtype: 'panel',
        title: 'Referencias Personales',
        autoScroll:true,
        items: [{
          
            items:
            	[{
                title: 'Referencias',
                layout: 'form',
                border:false,
                items: [forma_card_3]
            	}]
		}]
        

    },{
    
        id: 'card-3',
        xtype: 'panel',
        title: 'Informacion Crediticia',
        autoScroll:true,
        items: [{
          
            items:
            	[{
                title: 'Credito',
                layout: 'form',
                border:false,
                items: [forma_card_4]
            	}]
		}]

	    
            },{
				        id: 'card-4',
				        html: "<h2><p>Estas a punto de generar la solicitud, presiona el boton Finalizar para crear la solicitud.<br/>Tenga en cuenta que se generara el numero de la solicitud al finalizar</p></h2>"
				         
				
				}]

    
};
  
