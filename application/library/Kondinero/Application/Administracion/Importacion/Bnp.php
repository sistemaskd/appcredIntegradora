<?php
	/**
	 * Clase para importar la información del BNP
	 * @author emoreno
	 */
class Kondinero_Application_Administracion_Importacion_Bnp extends Kondinero_Application_Administracion_Importacion
{
	protected $_map = array();
	protected $_identifier = array();
	protected $_importType = 'bnp';
	
	/**
	 * Inicializamos modo de configuración
	 * y adaptamos la información para nuestros registros
	 */
	public function init()
	{
		parent::init();
		# --
		$content_map = $this->getContentMap();
		
		foreach($content_map as $content){
			if(    array_key_exists('tableClass',$content) 
			    && array_key_exists('content',$content)
			    && array_key_exists('identifier',$content)){
				$tableClass = @$content['tableClass'];
				$this->_map[$tableClass] = @$content['content'];
				$this->_identifier[$tableClass] = @$content['identifier'];
			} else {
				trigger_error("No se ha configurado el 'tableClass','content' o 'identifier' para la importación",E_USER_ERROR);
			}
		}
	}
	
	/**
	 * Realiza el guardado de la información DINAMICALLY baby!!!
	 */
	private function _save($content)
	{
		try{
			foreach($this->_map as $tableClass => $values){
				# Instanciamos la tabla
				$tableInstance = new $tableClass();	
				
				# Obtenemos las columnas reales de esta tabla
				$info = $tableInstance->info();
				$tableColumns = array_flip($info[Zend_Db_Table::COLS]);
				$primaryKey   = current($info[Zend_Db_Table::PRIMARY]);
				
				# - Obtenemos el campo del cual la información sera actualizada
				$updateValue = $this->_getValue($content,$this->_identifier[$tableClass],$updateField); 

				$data = array();
				# Iteramos los verdaderos valores que se van a insertar
				foreach($values as $fieldInformation){
					$updateContent = $this->_getValue($content, $fieldInformation, $field);
					if(trim($updateContent)){ 
						$data[$field] = $updateContent;
					}
				}
				
				# Actualizamos los datos si existe por lo menos 1 que actualizar
				if(empty($data)){
					# -
				} else {
					$where = $tableInstance->getAdapter()->quoteInto("{$updateField} = ?", $updateValue);
					if($tableInstance->update($data,$where)){
						# log para actualizacion BNP se utiliza la clase  Kondinero_Application_Log
						$keys = array_keys($data);
						$mensaje = 'Se actualizo la sig. info:'.implode(',',$keys).' con los valores '.implode(',', $data);
						$log= Kondinero_Application_Log::getLog($mensaje); 
					} else {
						# -
					}
				}
			}	
			
		} catch(Exception $e){
			$this->_isValid = FALSE;
			$this->_error = $e->getMessage();
		}
		
	}
	
	private function _getValue(&$content,$source,&$field = NULL,&$start = NULL,&$end = NULL)
	{
		$keys = array('field','start','end');
		
		$sourceInformation = array_pad($source, 3, 0);
		$sourceInformation = array_combine($keys, $sourceInformation);
		
		$field = $sourceInformation['field'];
		$start = $sourceInformation['start']-1;
		$end   = $sourceInformation['end'];
		
		return trim(substr($content,$start,$end-$start));
	}
	
	/**
	 * Procesa la linea que sera el registro que se guardara en la BD
	 * 
	 * - Cada registro de información esta en 1 linea
	 */
	protected function _processFile($path_file)
	{
		$lines = file($path_file);
		foreach($lines as $line_number => $line){
			$this->_save($line);
		}
	}
}