Ext.onReady(function(){
    Ext.QuickTips.init();
 
	// Create a variable to hold our EXT Form Panel. 
	// Assign various config options as seen.	 
    var login = new Ext.FormPanel({ 
        labelWidth:80,
        url: BASE_URL+'/login/process', 
        frame:true, 
        title:'Ingreso', 
        defaultType:'textfield',
        monitorValid:true,
	// Specific attributes for the text fields for username / password. 
	// The "name" attribute defines the name of variables sent to the server.
        items:[{ 
                fieldLabel:'Usuario', 
                name:'login', 
                allowBlank:false,
                blankText:"Este campo es requerido"
            },{ 
                fieldLabel:'Contrase&ntilde;a', 
                name:'password', 
                inputType:'password', 
                allowBlank:false,
                blankText:"Este campo es requerido"
            }],
 
	// All the magic happens after the user clicks the button     
        buttons:[{ 
                text:'Ingresar',
                formBind: true,	 
                // Function that fires when user clicks the button 
                handler:function(){ 
                    login.getForm().submit({ 
                        method:'POST',
                        waitTitle:'Conectando...', 
                        waitMsg:'Enviando datos...',
                        success:function(){ window.location = BASE_URL+'/main' },
                        failure:function(form, action){ 
                            if(action.failureType == 'server'){ 
                                obj = Ext.util.JSON.decode(action.response.responseText); 
                                Ext.Msg.alert('Error al ingresar', obj.errors.reason); 
                            }else{ 
                                Ext.Msg.alert('Advertencia!', 'El servidor de autenticación es inalcanzable" : ' + action.response.responseText); 
                            } 
                            login.getForm().reset(); 
                        } 
                    }); 
                } 
            }] 
    });
 
 
	// This just creates a window to wrap the login form. 
	// The login object is passed to the items collection.       
    var win = new Ext.Window({
        layout:'fit',
        width:300,
        height:150,
        closable: false,
        resizable: false,
        plain: true,
        border: false,
        draggable: false,
        items: [login]
	});
	win.show();
});