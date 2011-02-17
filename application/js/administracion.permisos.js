var administracionpermisos = new Ext.FormPanel({
	    id:'usuarios-panel',
        labelAlign: 'top',
        title: 'Administracion Usuarios',
        bodyStyle:'padding:5px',
        width: 600,
        height: 1100,
        autoScroll: true,
        url: BASE_URL+'/catalog/manage-users',
        method: 'POST',
        items: [{
            layout:'column',
            title: 'Usuarios',
            autoScroll: true,
            border:true,
            items:[{
                columnWidth:.4,
                layout: 'form',
                border:false,
                items: [{
                    xtype:'textfield',
                    fieldLabel: 'Nombre',
                    name: 'nombre',
                    allowBlank:false,
                    blankText:"Este campo es requerido",
                    anchor:'95%'
                }, {
                    xtype:'textfield',
                    fieldLabel: 'Usuario',
                    allowBlank:false,
                    blankText:"Este campo es requerido",
                    name: 'login',
                    anchor:'95%'
                },{
                    width: 275,
                    title: 'Permisos',
                   xtype:  'combo',
                   mode:   'local',
                  triggerAction:  'all',
                   forceSelection: true,
                   editable:       true,
                   allowBlank: false,
                   fieldLabel: 'Permisos',
                   name: 'rol',
                   hiddenName: 'rol',
                   displayField:'etiqueta',
                   valueField: 'valor',
                   store: storeBuilder.createStore('usuarios',{useCase:'usuario',field:'rol'})
                   
             }]
            },{
                columnWidth:.3,
                layout: 'form',
                border:false,
                items: [{
                    xtype:'textfield',
                    fieldLabel: 'Apellido Materno',
                    name: 'apellido_materno',
                    allowBlank:false,
                    blankText:"Este campo es requerido",
                    anchor:'95%'
                },{
                    xtype:'textfield',
                    fieldLabel: 'Password',
                    name: 'password',
                    inputType:'password', 
                    allowBlank:false,
                    blankText:"Este campo es requerido",
                   anchor:'95%'
                },{
                    width: 275,
                    title: 'Sucursal',
                   xtype:  'combo',
                   mode:   'local',
                  triggerAction:  'all',
                   forceSelection: true,
                   editable:      true,
                   allowBlank: false,
                   fieldLabel: 'Sucursal',
                   name: 'sucursal',
                   hiddenName: 'codigo',
                   displayField:'etiqueta', 
                   valueField: 'valor',
                   store: storeBuilder.createStore('usuarios',{useCase:'sucursal_usuario',field:'nombre'})
                   
             }]
            },{
                columnWidth:.3,
                layout: 'form',
                border:false,
               items: [{
                    xtype:'textfield',
                    fieldLabel: 'Apellido Paterno',
                    name: 'apellido_paterno',
                    allowBlank:false,
                    blankText:"Este campo es requerido",
                    anchor:'95%'
                },{
                    xtype:'textfield',
                    fieldLabel: 'Confirmar Password',
                    name: 'password_confirmacion',
                    allowBlank:false,
                    blankText:"Este campo es requerido",
                    inputType:'password', 
                    anchor:'95%'
                }]
            }]
        }],
            buttons: [{
                text: 'Guardar',
                handler: function(){ 
            		administracionpermisos.getForm().submit({ 
            		 waitTitle:'Conectando...', 
            			waitMsg:'Enviando datos...',
            		           		
                            success: function(form, action){
            			
            			obj = Ext.util.JSON.decode(action.response.responseText); 
                        Ext.Msg.alert('Exito', obj.message.reason); 
                            },
                            failure: function(form, action){ 
                                if(action.failureType == 'server'){ 
                                    obj = Ext.util.JSON.decode(action.response.responseText); 
                                    Ext.Msg.alert('Identificaci&oacute;n incorrecta', obj.errors.reason); 
                                }else{ 
                                    Ext.Msg.alert('¡Atenci&oacute;n!', 'Los campos so requeridos para poder crear el usuario ');
                                } 
                              
                            } 

                        });
            			
            			
            			
            			        	} 
            }]
        
        
});

