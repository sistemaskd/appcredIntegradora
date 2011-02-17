<?php
	/**
	 * Clase encargada de llevar acabo gestiones generales del guardado de la información
	 * @author emoreno
	 */
abstract class Kondinero_Application_Administracion_Importacion implements Kondinero_Application_Interface_Validator
{
	# - Validador
	protected $_isValid = TRUE;
	protected $_error;
	# -
	
	protected $_importType;
	protected $_contentMap;
	
	/**
	 * Inicializa el objeto con el import type asignado
	 */
	public function init()
	{
		$importacion = new Zend_Config_Ini(APPLICATION_PATH.'/configs/administracion/config.ini','importacion');
		
		# Debe ser una instancia de Zend_Config
		if($importacion instanceOf Zend_Config){
			$importacion = $importacion->toArray();

			$importType = $this->getImportType();
			# Verificamos que tenemos el import type dentro de nuestra configuracion
			if(array_key_exists($importType,$importacion)){
				$this->_contentMap = $importacion[$importType];
			} else {
				$this->_isValid = FALSE;
				$this->_error = "La configuración no contiene el import type '{$importType}'";
			}
		} else {
			trigger_error('No se configuro el import type en la configuración de importación', E_USER_ERROR);
		}
	}
	
	/**
	 * Lleva acabo la rutina de ejecución; es necesario sobreescribir
	 */
	protected abstract function _processFile($file_path); 
	
	/**
	 * Realiza la ejecución del adaptador
	 */
	final public function execute(Zend_File_Transfer_Adapter_Abstract $file_adapter)
	{
		$current_memory_limit = ini_get('memory_limit');
		$current_max_execution_time = ini_get('max_execution_time');
		
		ini_set('memory_limit','500M');
		ini_set('max_execution_time',600); # 10 Minutos		
		
		$path_file = $file_adapter->getFileName();
		$this->_processFile($path_file);
		
		ini_set('memory_limit',$current_memory_limit);
		ini_set('max_execution_time', $current_max_execution_time);
	}
	
	/**
	 * Devuelve si hubo exito en la carga
	 */
	public function isValid()
	{
		return $this->_isValid;
	}
	
	/**
	 * Regresa el error generado
	 */
	public function getError()
	{
		return $this->_error;
	}
	
	/**
	 * Accesor para _contentMap
	 */
	public function getContentMap()
	{
		return $this->_contentMap;
	}
	
	/**
	 * Accesor para importType
	 */
	public function setImportType($importType)
	{
		$this->_importType = $importType;
	}
	
	public function getImportType()
	{
		return $this->_importType;
	}
}