<?php
	/**
	 * Clase general para llevar acabo los pasos del wizard
	 * @author emoreno
	 */
class Kondinero_Application_Solicitud_Wizard_Step implements Kondinero_Application_Solicitud_Wizard_Step_Interface 
{
	protected $_data;
	protected $_tableClass;
	protected $_tableInstance;
	protected $_ids = array();
	
	/**
	 * Constructor de un paso
	 */
	public function __construct(array $data)
	{
		$this->_data = $data;
	}
	
	/**
	 * Lleva acabo la ejecución de cada paso
	 */
	public function execute()
	{
		$tableClassInstance = $this->getTable();
		$info = $tableClassInstance->info();
		
		$columns     = $info[Zend_Db_Table::COLS];
		$primary_key = $info[Zend_Db_Table::PRIMARY];
		
		if(in_array($primary_key,$columns)){
			# Borramos llave primaria del arreglo de campos
			$position = array_search($primary_key,$columns);
			unset($columns[$position]);
		} else {
			# -
		}

		$data = $this->_data;
		$dataToSave = array();

		foreach($columns as $columnName){
			if(array_key_exists($columnName,$data)){
				$dataToSave[$columnName] = $data[$columnName];
			} else {
				# -
			}
		}
		
		$dataInserts = array();
		
		# Si es un arreglo de datos entonces iteramos sobre cada uno y guardamos los valores
		if(is_array(current($dataToSave))){
			$keys = array_keys($dataToSave);
			$tmpArray = array();
			# -
			$dataNumber = count(current($dataToSave));
			# -
			for($i=0;$i<$dataNumber;$i++){
				$tmpArray[$i] = array();
				foreach($dataToSave as $data){
					$tmpArray[$i][] = (isset($data[$i])) ? $data[$i] : NULL;	
				}
				$tmpArray[$i] = array_combine($keys,array_values($tmpArray[$i]));
			}
			$dataInserts = $tmpArray;
		} else {
			$dataInserts[] = $dataToSave;
		}
		
		foreach($dataInserts as $key => $data){
			$tableClassInstance->insert($data);
			$this->addId($tableClassInstance->getAdapter()->lastInsertId());
		}

		return $this;
	}
	
	/**
	 * Accesor para id
	 */
	public function addId($id)
	{
		$this->_ids[] = $id;
	}
	
	/**
	 * Accesor para tableClass
	 */
	public function getTable()
	{
		$tableClassInstance = NULL;
		if($this->_tableInstance instanceOf Zend_Db_Table_Abstract){
			$tableClassInstance = $this->_tableInstance;
		} else {
			$this->_tableInstance = new $this->_tableClass();
			$tableClassInstance = $this->_tableInstance; 
		}
		
		return $tableClassInstance;
	}
	
	/**
	 * Accesor para ids
	 */
	public function getIds()
	{
		return $this->_ids;
	}
	
	/**
	 * Constructor de la clase
	 */
	public function setTableClass($tableClass = NULL)
	{
		$this->_tableClass = $tableClass;
	}
}