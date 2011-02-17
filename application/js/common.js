/**
 * Constantes para catalogos
 */
selectDescription = '-- Seleccionar --';

/*
 * Crea un Store al vuelo con la configuración especificada por el usuario
 * @author Ezequiel Moreno Garcia
 */

storeBuilder = {};
storeBuilder.storeNumbers = 0;
storeBuilder.entities = {};

storeBuilder.createStore = function(entity, userBaseParams)
{
	var reader = new Ext.data.JsonReader({
		root: false
		},[{name:'valor'},{name:'etiqueta'}]
		);

	var proxy = new Ext.data.HttpProxy({
		url: BASE_URL+'/catalog/get-data',
		method: 'POST'
		});

	var store = new Ext.data.Store({
		proxy: proxy,
		reader: reader,
		baseParams : userBaseParams
		});
	
	if(typeof(this.entities[entity]) == 'undefined'){
		this.entities[entity] = {};
		this.entities[entity].isLoaded = false;
		
		if(typeof(this.entities[entity].stores) == 'undefined'){
			this.entities[entity].stores = {};
		} else {
			// Store para la entidad ya definida
		}
	} else {
		// Entidad para el objeto ya definido
	}
	
	this.entities[entity].stores[this.storeNumbers++] = store;
	
	return store;
}

// Realiza el cargado de los stores
storeBuilder.load = function(entityName)
{
	if(this.entities[entityName].isLoaded){
		// - Ya fue cargada esta entidad
	} else {
		var entities = this.entities[entityName].stores;
		for(store in entities){
			entities[store].load();
		}
		this.entities[entityName].isLoaded = true;
	}
}
// Recarga un store dado su nombre
storeBuilder.reload = function(entityName)
{
	if(   this.entities[entityName] != 'undefined' 
	   && this.entities[entityName].stores != 'undefined'){
		entities = this.entities[entityName].stores;
		for(store in entities){
			entities[store].reload();
		}	
	} else {
		// -
	}
}
/*
 * End Stores
 */