var info = new Ext.FormPanel({
	id: 'info-panel',
	title: 'Carga Info 963',
    labelAlign: 'top',
    frame:true,
    fileUpload: true,
     items: [{
        layout:'column',
        bodyStyle: 'padding: 10px 10px 0 10px;',
        items:[{
            columnWidth:1,
            layout: 'form',
            title: 'Carga Info 963',
        
            items: [{
            xtype: 'textfield',
            fieldLabel: 'Nombre del Archivo',
            anchor:'60%',
            allowBlank: false,
            },{
                xtype: 'hidden',
                name: 'importType',
                value: 'info963'
            },{
            xtype: 'fileuploadfield',
            id: 'archivo',
            emptyText: 'Seleccione el archivo a cargar',
            fieldLabel: 'Archivo de Carga ',
            allowBlank: false,
            name: 'info-path',
            anchor:'60%',
            buttonText: '',
            buttonCfg: {
                iconCls: 'upload-icon'
            }
        }],
        buttons: [{
            text: 'Guardar',
            handler: function(){
                if(info.getForm().isValid()){
                	info.getForm().submit({
	                    url: BASE_URL+'/administracion/import',
	                    waitMsg: 'Cargando y sincronizando informaci&oacute;n, espere un momento...',
	                    success: function(response){
                			var message = 'La ejecuci&oacute;n se realizo con exito';
                			Ext.Msg.alert('Correcto', message);
	                    },
                		failure:function(form,action){
	                    	var obj = Ext.util.JSON.decode(action.response.responseText);
	                    	callback = function(){ info.getForm().reset(); }
	                    	Ext.Msg.alert('Fallido', obj.errors.reason, callback); 
	                    }
	                });
                }
            }
        },{
            text: 'Cancelar',
            handler: function(){
        		info.getForm().reset();
            }
        }]
        }]
     }]
   });

