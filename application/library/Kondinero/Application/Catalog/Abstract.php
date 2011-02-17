<?php
	/**
	 * Clase abstracta con funcionalidad default añadida
	 * @author emoreno
	 */
abstract class Kondinero_Application_Catalog_Abstract
{
	protected $_table;
	protected $_tableClass;
	protected $_useCase;
	
	/**
	 * Constructor de la clase
	 */
	public function __construct($tableClass = NULL)
	{
		$tableInstance = NULL;
		if($tableClass){
			$tableInstance = new $tableClass();
		} else if($this->_tableClass){
			$tableInstance = new $this->_tableClass();
		} else {
			trigger_error('No se configuro una instancia para el catalogo',E_USER_ERROR);
		}
		$this->setTable($tableInstance);
	}
	
	/**
	 * Accesor para la tabla
	 */
	public function setTable(Zend_Db_Table_Abstract $table)
	{
		$this->_table = $table;
	}
	
	/**
	 * Devuelve la tabla del catalogo
	 */
	public function getTable()
	{
		return $this->_table;
	}
	
	/**
	 * Devuelve los valores de catalogo para la tabla especificada
	 */
	public function getFromEnumField($field)
	{
		$data = array();
		$enumValues;
		
		if($this->getDataType($field, $enumValues) == 'enum'){
			$enumValues = self::getEnumValues($enumValues);
		} else {
			$enumValues = array();
		}
		
		$data = $this->_processValues($field, $enumValues);
		
		return $data;
	}
	
	/**
	 * Devuelve todos los valores de un catalogo
	 */
	public function getFromCatalog($field)
	{
		$data = array();
		
		$table = $this->getTable();
		$info = $table->info();
		
		$columns = $info[Zend_Db_Table::COLS];
		
		# Verificamos si existe el campo a consultar
		if(in_array($field, $columns)){
			
			$primaryKey = current($info[Zend_Db_Table::PRIMARY]);
			
			$columns = array('valor'	=> $primaryKey
			                ,'etiqueta'	=> $field);
			
			$select = $table->getAdapter()->select();
			$select->from($info[Zend_Db_Table::NAME],$columns);

			$this->preProcessSelect($select);
			#die($select);
			$statement = $select->query();
			$data = $statement->fetchAll();
			
		} else {
			# -
		}
		
		return $data;
	}
	
	/**
	 * Preprocesa el select para añadir funcionalidad especial
	 */
	public function preProcessSelect(Zend_Db_Select &$select)
	{
		return TRUE;
	} 
	
	/**
	 * Procesa la información de salida
	 */
	protected function _processValues($field, $values) 
	{
		$processedValues = array();
		$config  = new Zend_Config_Ini(APPLICATION_PATH.'/configs/catalog/config.ini','useCase');
		$useCase = $this->getUseCase();
		
		$fieldsAttributes = @$config->$useCase->field->$field;
		$fieldsAttributes = $fieldsAttributes ? $fieldsAttributes->toArray() : array();
		
		foreach($values as $value){
			$label = $value;
			foreach($fieldsAttributes as $attribute){
				if(strtolower($value) == strtolower(@$attribute['forValue'])){
					$label = @$attribute['label'];
					break;	
				} else {
					# Continuamos con el siguiente
				}
			}
			$label = $label ? $label : $value;
			$processedValues[] = array('valor' => $value, 'etiqueta' => $label);
		}
		return $processedValues;
	}
	
	/**
	 * Devuelve los valores del campo enumerador
	 */
	public static function getEnumValues($enumString)
	{
		return explode(',',str_replace(array('enum',"(","'",")"),array('','','','',''),$enumString));
	}
	
	/**
	 * Devuelve el tipo de dato para el campo especificado
	 * @param string $field
	 */
	public function getDataType($field, &$dataSetValues = NULL)
	{
		$dataType = NULL;
		$table = $this->getTable();
		$info = $table->info();
		
		$metadata = $info[Zend_Db_Table::METADATA];
		
		if(array_key_exists($field,$metadata)){
			$dataType = $metadata[$field]['DATA_TYPE']; #Harcoded dado a que no es estandar, mysql owned
			
			$enum = 'enum';
			if(substr($dataType,0,strlen($enum)) == $enum){
				$dataSetValues = $dataType;
				$dataType = $enum;
			} else {
				# -
			}
			
		} else {
			# El campo no existe en los metadatos de la tabla
		}
		
		return $dataType;
	}
	
	/**
	 * Accesor para useCase
	 */
	public function setUseCase($useCase)
	{
		$this->_useCase = $useCase;
	}
	
	/**
	 * Accesor para useCase
	 */
	public function getUseCase()
	{
		return $this->_useCase;
	}
}