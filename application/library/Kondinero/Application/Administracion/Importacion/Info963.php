<?php
	/**
	 * Clase para importar la información del INFO963
	 * @author emoreno
	 */
class Kondinero_Application_Administracion_Importacion_Info963 extends Kondinero_Application_Administracion_Importacion
{
	protected $_map;
	protected $_importType = 'info963';
	
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
			if(array_key_exists('tableClass',$content) && array_key_exists('content',$content)){
				$tableClass = @$content['tableClass'];
				$this->_map[$tableClass] = @$content['content'];
			} else {
				trigger_error("No se ha configurado el 'tableClass' ni el 'content' para la importación",E_USER_ERROR);
			}
		}
	}
	
	/**
	 * Realiza el guardado de la información DINAMICALLY baby!!!
	 */
	private function _save(array $content)
	{
		$ids = array();
		$postData = array();
		
		try{
			
			$solicitud = new Kondinero_Application_Solicitud();
			$tableName = get_class($solicitud->getTable());
			
			if(array_key_exists($tableName,$this->_map)){
				$solicitudData = $this->_map[$tableName];
				unset($this->_map[$tableName]);
				
				foreach($solicitudData as $_position => $_columnName){
					if(isset($content[$_position])){
						$postData[$_columnName] = $content[$_position]; 
					} else {
						# -
					}
				}
				
			} else {
				# -
			}
			
			foreach($this->_map as $tableClass => $values){
				# Instanciamos la tabla
				$tableInstance = new $tableClass();	
				
				# Obtenemos las columnas reales de esta tabla
				$info = $tableInstance->info();
				$tableColumns = array_flip($info[Zend_Db_Table::COLS]);
				$primaryKey   = current($info[Zend_Db_Table::PRIMARY]);
				
				$data = array();
				# Iteramos los verdaderos valores que se van a insertar
				foreach($values as $position => $columnName){
					if(   array_key_exists($columnName,$tableColumns)
					   && isset($content[$position])){
						$data[$columnName] = $content[$position];
					} else {
						# -
					}
				}
				
				# Insertamos los datos si existe por lo menos 1 que insertar
				if(empty($data)){
					# -
				} else {
					$tableInstance->insert($data);
					$ids[$primaryKey] = $tableInstance->getAdapter()->lastInsertId();
				}

			}	
			
			# Se registro por lo menos una relacion; entonces crearemos la solicitud
			# y relacionaremos sus llaves
			if(count($ids)){
				# - Creacion de la solicitud
				$data = array_merge($postData,$ids);
				$data['fecha_creacion'] = date('Y-m-d H:i:s');

				$solicitud->insertData($data);
			} else {
				# -
			}
			
		} catch(Exception $e){
			$this->_isValid = FALSE;
			$this->_error = $e->getMessage();
		}
		
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
			$values = $this->_processLine($line);
			$this->_save($values);
		}
	}
	
	/**
	 * Procesa cada linea del archivo
	 * 
	 * - Esta separado por comas ','
	 */
	private function _processLine($line)
	{
		return explode(',',$line);
	}
}